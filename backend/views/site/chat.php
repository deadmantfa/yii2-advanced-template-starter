<?php

use yii\web\View;

$this->title = 'Chat';
?>
    <div class="row">
        <div class="col-md-3">
            <!-- DIRECT CHAT PRIMARY -->
            <div class="card card-primary card-outline direct-chat direct-chat-primary">
                <div class="card-header">
                    <h3 class="card-title">Direct Chat</h3>

                    <div class="card-tools">
                        <span title="3 New Messages" class="badge bg-primary">3</span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
                            <i class="fas fa-id-badge text-green"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <!-- Conversations are loaded here -->
                    <div class="direct-chat-messages">
                        <!-- Message. Default to the left -->
                        <!-- Template for chat from someone else -->
                        <!-- /.direct-chat-msg -->

                        <!-- Message to the right -->
                        <!-- Template for chat from you -->
                        <!-- /.direct-chat-msg -->
                    </div>
                    <!--/.direct-chat-messages-->

                    <!-- Contacts are loaded here -->

                    <div class="direct-chat-contacts">
                        <ul class="contacts-list">
                        </ul>
                        <!-- /.contatcts-list -->
                    </div>
                    <!-- /.direct-chat-pane -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <form name="sendChat">
                        <div class="input-group">
                            <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                            <span class="input-group-append">
                                <button type="submit" class="btn btn-primary">Send</button>
                            </span>
                        </div>
                    </form>
                </div>
                <!-- /.card-footer-->
            </div>
            <!--/.direct-chat -->
        </div>
    </div>
    <template id="chat-from">
        <div class="direct-chat-msg">
            <div class="direct-chat-infos clearfix">
                <span class="direct-chat-name float-left">Sample Name</span>
                <span class="direct-chat-timestamp float-right">Sample Time</span>
            </div>
            <!-- /.direct-chat-infos -->
            <img class="direct-chat-img" src="" alt="Message User Image">
            <!-- /.direct-chat-img -->
            <div class="direct-chat-text">
            </div>
            <!-- /.direct-chat-text -->
        </div>
    </template>
    <template id="chat-to">
        <div class="direct-chat-msg right">
            <div class="direct-chat-infos clearfix">
                <span class="direct-chat-name float-right"><?= Yii::$app->user->identity->profile->name ?></span>
                <span class="direct-chat-timestamp float-left">Sample Time</span>
            </div>
            <!-- /.direct-chat-infos -->
            <img class="direct-chat-img" src="<?= Yii::$app->user->identity->profile->getAvatarUrl(160) ?>"
                 alt="<?= Yii::$app->user->identity->username ?>">
            <!-- /.direct-chat-img -->
            <div class="direct-chat-text">
                Sample Message
            </div>
            <!-- /.direct-chat-text -->
        </div>
    </template>
    <template id="contact-list">
        <li>
            <a href="#">
                <img class="contacts-list-img" src=""
                     alt="User Avatar">
                <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            Sample Name
                            <small class="contacts-list-date float-right">Sample time</small>
                          </span>
                </div>
                <!-- /.contacts-list-info -->
            </a>
        </li>
        <!-- End Contact Item -->
    </template>

<?php
$this->registerJs(
    '
let wsLink = "' . Yii::$app->params['ws-link'] . '";
let currentUser = "' . Yii::$app->user->identity->username . '";
let fullName = "' . Yii::$app->user->identity->profile->name . '";
let avatarImg = "' . Yii::$app->user->identity->profile->getAvatarUrl(160) . '";
        ',
    View::POS_BEGIN
);
$this->registerJsFile(
    '@web/js/chat.js',
    [],
    View::POS_END
);
