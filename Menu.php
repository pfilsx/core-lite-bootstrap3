<?php


namespace core\bootstrap;


use core\exceptions\ErrorException;
use core\helpers\ArrayHelper;
use core\web\Html;

class Menu extends \core\widgets\menu\Menu
{

    protected $_class = '';
    protected $_class_horizontal = 'nav nav-pills';
    protected $_class_vertical = 'list-group';

    protected function render(){
        echo Html::startTag('ul', ArrayHelper::merge_recursive($this->options, ['class' => $this->_class]));
        foreach ($this->items as $item){
            if (!isset($item['label']) || (!isset($item['url']) && (!isset($item['items']) || !is_array($item['items'])))){
                throw new ErrorException('Invalid parameters passed to Menu::widget items');
            }
            $this->renderItem($item);
        }
        echo Html::endTag('ul');
    }
    protected function renderItem($item){
        if ($this->orientation == 'horizontal'){
            $this->renderHorizontalItem($item);
        } else {
            $this->renderVerticalItem($item);
        }
    }

    private function renderHorizontalItem($item){
        if (isset($item['items'])){

            echo Html::startTag('li', ArrayHelper::merge_recursive([
                'role' => 'presentation',
                'class' => 'dropdown'
            ],$this->itemOptions));
            echo Html::tag('a', $item['label'].' '.Html::tag('span', ['class' => 'caret']), [
                'class' => 'dropdown-toggle',
                'data-toggle' => 'dropdown',
                'href' => '#',
                'role' => 'button',
                'aria-haspopup' => 'true',
                'aria-expanded' => 'false'
            ]);
            echo Html::startTag('ul', ['class' => 'dropdown-menu']);
            foreach ($item['items'] as $subItem){
                echo Html::tag('li', Html::tag('a', $subItem['label'], ['href' => $subItem['url']]),
                    isset($subItem['options']) ? $subItem['options'] : []);
            }
            echo Html::endTag('ul');
            echo Html::endTag('li');

        } else {
            echo Html::startTag('li', ArrayHelper::merge_recursive($this->itemOptions, [
                'class' => ($this->_currentUrl == $item['url'] || $this->_currentRoute == $item['url']
                    ? 'active'
                    : ''),
                'role' => 'presentation'
            ]));
            echo Html::tag('a', $item['label'], [
                'href' => $item['url']
            ]);
            echo Html::endTag('li');
        }
    }
    private function renderVerticalItem($item){

    }
    protected function renderVertical()
    {
        echo Html::startTag('div', ArrayHelper::merge_recursive($this->options, ['class' => 'list-group']));
        foreach ($this->items as $item){
            if (!isset($item['label']) || !isset($item['url'])){
                throw new \Exception('Invalid parameters passed to Menu::widget items');
            }
            echo Html::tag('a', $item['label'], ArrayHelper::merge_recursive($this->itemOptions, [
                'class' => ($this->_currentUrl == $item['url'] || $this->_currentRoute == $item['url']
                    ? 'list-group-item active'
                    : 'list-group-item'),
                'href' => $item['url']
            ]));
        }
        echo Html::endTag('div');
    }
}