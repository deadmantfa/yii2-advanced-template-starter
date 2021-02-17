<?php

namespace backend\components;

use backend\assets\NotificationsAsset;
use kartik\helpers\Html;
use webzop\notifications\widgets\Notifications;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;

class NotificationWidget extends Notifications
{

    public array $icons = ['class' => 'far fa-bell'];

    /**
     * Registers the needed assets
     */
    public function registerAssets(): void
    {
        $this->clientOptions = array_merge([
            'id' => $this->options['id'],
            'url' => Url::to(['/notifications/default/list']),
            'countUrl' => Url::to(['/notifications/default/count']),
            'readUrl' => Url::to(['/notifications/default/read']),
            'readAllUrl' => Url::to(['/notifications/default/read-all']),
            'xhrTimeout' => \yii\helpers\Html::encode($this->xhrTimeout),
            'pollInterval' => Html::encode($this->pollInterval),
        ], $this->clientOptions);

        $js = 'Notifications(' . Json::encode($this->clientOptions) . ');';
        $view = $this->getView();

        NotificationsAsset::register($view);

        $view->registerJs($js);
    }

    /**
     * @inheritdoc
     */
    protected function renderNavbarItem(): string
    {
        $html = Html::beginTag('li', $this->options);
        $html .= Html::beginTag('a', ['href' => '#', 'class' => 'nav-link', 'data-toggle' => 'dropdown']);
        $html .= Html::tag('i', '', $this->icons);

        $count = self::getCountUnseen();
        $countOptions = array_merge([
            'tag' => 'span',
            'data-count' => $count,
        ], $this->countOptions);
        Html::addCssClass($countOptions, 'label label-warning navbar-badge notifications-count');
        if (!$count) {
            $countOptions['style'] = 'display: none;';
        }
        $countTag = ArrayHelper::remove($countOptions, 'tag', 'span');
        $html .= Html::tag($countTag, $count, $countOptions);

        $html .= Html::endTag('a');
        $html .= Html::begintag('div', ['class' => 'dropdown-menu dropdown-menu-lg dropdown-menu-right']);
        $html .= Html::tag('span', $count . ' Notification(s)', ['class' => 'dropdown-header']);

        $html .= Html::begintag('div', ['class' => 'notifications-list']);
        $html .= Html::tag('div', Html::tag('span', Yii::t('modules/notifications', 'There are no notifications to show'), ['style' => 'display: none;']), ['class' => 'empty-row']);
        $html .= Html::endTag('div');

        $html .= Html::tag('div', '', ['class' => 'dropdown-divider']);
        $html .= Html::beginTag('div', ['class' => 'row']);
        $html .= Html::beginTag('div', ['class' => 'col px-0']);
        $html .= Html::a(Yii::t('modules/notifications', 'View All'), ['/notifications/default/index'], ['class' => 'dropdown-item bg-dark dropdown-footer']);
        $html .= Html::endTag('div');
        $html .= Html::beginTag('div', ['class' => 'col px-0']);
        $html .= Html::a(Yii::t('modules/notifications', 'Mark all as read'), ['/notifications/default/read-all'], ['class' => 'dropdown-item bg-blue dropdown-footer']);
        $html .= Html::endTag('div');
        $html .= Html::endTag('div');
        $html .= Html::endTag('div');
        $html .= Html::endTag('li');

        return $html;
    }
}
