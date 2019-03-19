<?php

/* @var $this yii\web\View */

$this->title = 'WordParser';
?>
<div class="site-index">
    <div class="row">
        <div class="col-md-8">
            <div class="list-group" id="list-tab" role="tablist">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Исходный текст
                    </div>
                    <div class="panel-body">
                        <?php if(empty($parser->getContent())):?>
                            <div class="alert alert-warning">
                                Пусто
                            </div>
                        <?php else:?>
                        <?=$parser->getContent()?>
                        <?php endif;?>
                    </div>
                    <div class="panel-footer">
                        Контент берется из файла some.txt, который находится в папке <b>web</b>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="list-group" id="list-tab" role="tablist">
                <?php foreach ($parser->getResult() as $item):?>
                    <a class="list-group-item list-group-item-action"><?=$item['word']?><span class="badge badge-light"><?=$item['count']?></span></a>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</div>
