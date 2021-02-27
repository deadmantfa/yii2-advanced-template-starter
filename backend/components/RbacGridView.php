<?php


namespace backend\components;


use kartik\grid\GridView;

class RbacGridView extends GridView
{
    /**
     * @inheritdoc
     * @var string
     */
    public $layout = '
		{items}
		<div class="row">
			<div class="col-md-6">{summary}</div>
			<div class="col-md-6 text-right">{pager}</div>
		</div>';

}
