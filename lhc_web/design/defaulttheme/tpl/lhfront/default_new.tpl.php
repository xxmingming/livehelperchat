<?php include(erLhcoreClassDesign::designtpl('lhfront/online_chat_enabled_pre.tpl.php')); ?>

<?php if ($online_chat_enabled_pre == true || $online_visitors_enabled_pre == true) : ?>

    <?php
    $canListOnlineUsers = false;
    $canListOnlineUsersAll = false;
    $currentUser = erLhcoreClassUser::instance();

    if (erLhcoreClassModelChatConfig::fetch('list_online_operators')->current_value == 1) {
        $canListOnlineUsers = $currentUser->hasAccessTo('lhuser', 'userlistonline');
        $canListOnlineUsersAll = $currentUser->hasAccessTo('lhuser', 'userlistonlineall');
    }

    $canseedepartmentstats = $currentUser->hasAccessTo('lhuser', 'canseedepartmentstats');

    $pendingTabEnabled = erLhcoreClassModelUserSetting::getSetting('enable_pending_list', 1);
    $activeTabEnabled = erLhcoreClassModelUserSetting::getSetting('enable_active_list', 1);

    $closedTabEnabled = erLhcoreClassModelUserSetting::getSetting('enable_close_list', 0) && erLhcoreClassModelChatConfig::fetchCache('list_closed')->current_value == 1;

    $unreadTabEnabled = erLhcoreClassModelUserSetting::getSetting('enable_unread_list', 0) && erLhcoreClassModelChatConfig::fetchCache('list_unread')->current_value == 1;

    $mchatsTabEnabled = erLhcoreClassModelUserSetting::getSetting('enable_mchats_list', 1);

    $frontTabsOrder = explode(',', erLhcoreClassModelChatConfig::fetch('front_tabs')->current_value);
    ?>

    <?php include(erLhcoreClassDesign::designtpl('lhchat/onlineusers/online_settings_general.tpl.php')); ?>

    <div ng-controller="OnlineCtrl as online" ng-init='online.forbiddenVisitors=<?php $currentUser->hasAccessTo('lhchat', 'use_onlineusers') != true ? print 'true' : print 'false'?>;groupByField = <?php echo json_encode($ogroupBy) ?>;online.maxRows="<?php echo (int)$omaxRows ?>";online.time_on_site = <?php echo json_encode($oTimeOnSite)?>;online.country="<?php echo htmlspecialchars($oCountry)?>";online.updateTimeout="<?php echo (int)$oupdTimeout ?>";online.userTimeout = "<?php echo (int)$ouserTimeout ?>";online.department="<?php echo (int)$onlineDepartment ?>";online.soundEnabled=<?php echo $soundUserNotification == 1 ? 'true' : 'false' ?>;online.notificationEnabled=<?php echo $browserNotification == 1 ? 'true' : 'false' ?>'>

        <div class="row">
            <div class="col chats-column border-right pr-0 pl-0">

                <div class="w-100 d-flex flex-column flex-grow-1">
                    <div class="clearfix">
                        <div class="text-muted p-2 float-left"><i class="material-icons mr-0">list</i><span class="fs13 font-weight-bold"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('front/default', 'Open chats'); ?></span></div>
                        <a class="d-inline-block pt-2 pr-2 float-right"  onclick="return lhc.revealModal({'url':WWW_DIR_JAVASCRIPT +'chat/dashboardwidgets'})" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/syncadmininterface','Configure dashboard')?>"><i class="material-icons mr-0">&#xE871;</i></a>
                        <?php include(erLhcoreClassDesign::designtpl('lhchat/lists/open_active_chat_tab.tpl.php')); ?>
                    </div>
                    <div id="tabs-dashboard" class="border-top">
                    </div>

                    <div class="dashboard-panels border-top d-flex flex-column flex-grow-1" style="position:relative">
                    <?php $hideCard = true; ?>
                    <?php include(erLhcoreClassDesign::designtpl('lhchat/lists_panels/right_panel_container.tpl.php'));?>
                    </div>
                </div>
            </div>
            <div class="col">

                <div role="tabpanel" id="tabs" ng-cloak>
                    <ul class="nav nav-pills" role="tablist">
                        <?php foreach ($frontTabsOrder as $frontTab) : ?>
                            <?php if (trim($frontTab) == 'online_users' && $online_visitors_enabled_pre == true) : ?>
                                <?php include(erLhcoreClassDesign::designtpl('lhchat/onlineusers/section_online_users_tab.tpl.php')); ?>
                            <?php elseif (trim($frontTab) == 'online_map' && $online_visitors_enabled_pre == true) : ?>
                                <?php include(erLhcoreClassDesign::designtpl('lhchat/onlineusers/section_map_online_tab.tpl.php')); ?>
                            <?php elseif (trim($frontTab) == 'pending_chats') : ?>
                                <?php if ($pendingTabEnabled == true && $online_chat_enabled_pre == true) : ?>
                                    <?php include(erLhcoreClassDesign::designtpl('lhchat/lists/angular_pending_list_tab.tpl.php')); ?>
                                <?php endif; ?>
                            <?php elseif (trim($frontTab) == 'active_chats') : ?>
                                <?php if ($activeTabEnabled == true && $online_chat_enabled_pre == true) : ?>
                                    <?php include(erLhcoreClassDesign::designtpl('lhchat/lists/angular_active_list_tab.tpl.php')); ?>
                                <?php endif; ?>
                            <?php elseif (trim($frontTab) == 'unread_chats') : ?>
                                <?php if ($unreadTabEnabled == true && $online_chat_enabled_pre == true) : ?>
                                    <?php include(erLhcoreClassDesign::designtpl('lhchat/lists/angular_unread_list_tab.tpl.php')); ?>
                                <?php endif; ?>
                            <?php elseif (trim($frontTab) == 'closed_chats') : ?>
                                <?php if ($closedTabEnabled == true && $online_chat_enabled_pre == true) : ?>
                                    <?php include(erLhcoreClassDesign::designtpl('lhchat/lists/angular_closed_list_tab.tpl.php')); ?>
                                <?php endif; ?>
                            <?php elseif (trim($frontTab) == 'dashboard') : ?>
                                <?php if ($online_chat_enabled_pre == true) : ?>
                                    <?php include(erLhcoreClassDesign::designtpl('lhchat/lists/angular_dashboard_tab.tpl.php')); ?>
                                <?php endif; ?>
                            <?php elseif (trim($frontTab) == 'online_operators') : ?>
                                <?php if ($canListOnlineUsers == true || $canListOnlineUsersAll == true) : ?>
                                    <?php include(erLhcoreClassDesign::designtpl('lhchat/lists/angular_online_op_list_tab.tpl.php')); ?>
                                <?php endif; ?>
                            <?php else : ?>
                                <?php include(erLhcoreClassDesign::designtpl('lhchat/lists/angular_custom_list_tab_multiinclude.tpl.php')); ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                    <div class="tab-content" ng-cloak>
                        <?php foreach ($frontTabsOrder as $frontTab) : ?>
                            <?php if (trim($frontTab) == 'online_users' && $online_visitors_enabled_pre == true) : ?>
                                <div role="tabpanel" class="tab-pane form-group" id="onlineusers">
                                    <div>
                                        <?php include(erLhcoreClassDesign::designtpl('lhchat/onlineusers/section_online_users.tpl.php')); ?>
                                    </div>
                                </div>
                            <?php elseif (trim($frontTab) == 'online_map' && $online_visitors_enabled_pre == true) : ?>
                                <div role="tabpanel" class="tab-pane form-group" id="map">
                                    <div>
                                        <?php include(erLhcoreClassDesign::designtpl('lhchat/onlineusers/section_map_online.tpl.php')); ?>
                                    </div>
                                </div>
                            <?php elseif (trim($frontTab) == 'pending_chats' && $online_chat_enabled_pre == true) : ?>
                                <?php if ($pendingTabEnabled == true) : ?>
                                    <div role="tabpanel" class="tab-pane form-group" id="pendingchats">
                                        <div id="pending-chat-list">
                                            <?php include(erLhcoreClassDesign::designtpl('lhchat/lists/angular_pending_list.tpl.php')); ?>
                                        </div>
                                        <a class="btn btn-secondary btn-sm" href="<?php echo erLhcoreClassDesign::baseurl('chat/list') ?>/(chat_status_ids)/0"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('front/default', 'All pending chats'); ?></a>
                                    </div>
                                <?php endif; ?>
                            <?php elseif (trim($frontTab) == 'active_chats' && $online_chat_enabled_pre == true) : ?>

                                <?php if ($activeTabEnabled == true) : ?>
                                    <div role="tabpanel" class="tab-pane form-group" id="activechats">
                                        <div id="active-chat-list">
                                            <?php include(erLhcoreClassDesign::designtpl('lhchat/lists/angular_active_list.tpl.php')); ?>
                                        </div>
                                        <a class="btn btn-secondary btn-sm" href="<?php echo erLhcoreClassDesign::baseurl('chat/list') ?>/(chat_status_ids)/1"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('front/default', 'All active chats'); ?></a>
                                    </div>
                                <?php endif; ?>

                            <?php elseif (trim($frontTab) == 'unread_chats' && $online_chat_enabled_pre == true) : ?>

                                <?php if ($unreadTabEnabled == true) : ?>
                                    <div role="tabpanel" class="tab-pane form-group" id="unreadchats">
                                        <div id="unread-chat-list"><?php include(erLhcoreClassDesign::designtpl('lhchat/lists/angular_unread_list.tpl.php')); ?></div>
                                        <a class="btn btn-secondary btn-sm" href="<?php echo erLhcoreClassDesign::baseurl('chat/list') ?>/(hum)/1"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('front/default', 'All unread chats'); ?></a>
                                    </div>
                                <?php endif; ?>

                            <?php elseif (trim($frontTab) == 'closed_chats' && $online_chat_enabled_pre == true) : ?>

                                <?php if ($closedTabEnabled == true) : ?>
                                    <div role="tabpanel" class="tab-pane form-group" id="closedchats">
                                        <div id="closed-chat-list"><?php include(erLhcoreClassDesign::designtpl('lhchat/lists/angular_closed_list.tpl.php')); ?></div>
                                        <a class="btn btn-secondary btn-sm" href="<?php echo erLhcoreClassDesign::baseurl('chat/list') ?>/(chat_status_ids)/2"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('front/default', 'All closed chats'); ?></a>
                                    </div>
                                <?php endif; ?>

                            <?php elseif (trim($frontTab) == 'dashboard' && $online_chat_enabled_pre == true) : ?>

                                <div role="tabpanel" class="tab-pane form-group" id="dashboard">
                                    <?php include(erLhcoreClassDesign::designtpl('lhfront/dashboard/index.tpl.php')); ?>
                                </div>

                            <?php elseif (trim($frontTab) == 'online_operators') : ?>

                                <?php if ($canListOnlineUsers == true || $canListOnlineUsersAll == true) : ?>
                                    <div role="tabpanel" class="tab-pane form-group" id="onlineoperators">
                                        <div id="online-operator-list"><?php include(erLhcoreClassDesign::designtpl('lhchat/lists/angular_online_op_list.tpl.php')); ?></div>
                                    </div>
                                <?php endif; ?>
                            <?php else : ?>
                                <?php include(erLhcoreClassDesign::designtpl('lhchat/lists/angular_custom_list_multiinclude.tpl.php')); ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function () {
            //lhinst.attachTabNavigator();
            $('#right-column-page').removeAttr('id');
            $('#tabs a:first').tab('show')
        });
    </script>
<?php else : ?>

    <?php include(erLhcoreClassDesign::designtpl('lhfront/default_if_no_module.tpl.php')); ?>

<?php endif; ?>