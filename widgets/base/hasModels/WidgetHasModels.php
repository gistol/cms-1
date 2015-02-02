<?php
/**
 * WidgetHasModels
 *
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010-2014 SkeekS (Sx)
 * @date 02.02.2015
 * @since 1.0.0
 */
namespace skeeks\cms\widgets\base\hasModels;

use skeeks\cms\base\Widget;
use skeeks\cms\models\Publication;
use skeeks\cms\models\Search;
use skeeks\cms\models\Tree;
use skeeks\cms\widgets\base\hasTemplate\WidgetHasTemplate;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;

/**
 * Class WidgetHasModels
 * @package skeeks\cms\widgets\base\hasModels
 */
class WidgetHasModels extends WidgetHasTemplate
{
    public $modelClassName          = null;

    public $defaultSortField        = 'id';
    public $defaultSort             = SORT_DESC;

    public $defaultPageSize         = 10;

    /**
     * @return string
     */
    public function getModelClassName()
    {
        $className = \Yii::$app->registeredModels->getClassNameByCode($this->modelClassName);
        if ($className)
        {
            return (string) $className;
        } else
        {
            return (string) $this->modelClassName;
        }
    }


    /**
     * @return Search
     */
    public function getSearch()
    {
        return $this->_data->search;
    }

    public function buildSearch()
    {
        $modelClassName = $this->getModelClassName();
        $search         = new Search($modelClassName);

        $dataProvider = $search->getDataProvider();
        $dataProvider->getPagination()->defaultPageSize = $this->defaultPageSize;

        if ($this->defaultSortField)
        {
            $dataProvider->getSort()->defaultOrder = [
                $this->defaultSortField => $this->defaultSort
            ];
        }

        $this->_data->set('dataProvider',   $dataProvider);
        $this->_data->set('search',         $search);
    }
    /**
     * Подготовка данных для шаблона
     * @return $this
     */
    public function bind()
    {
        $this->buildSearch();
        $this->_data->search->search(\Yii::$app->request->queryParams);
        return $this;
    }

}