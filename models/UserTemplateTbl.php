<?php
namespace psc\models;

use psc\Module;

class UserTemplateTbl extends \yii\db\ActiveRecord
{
  public static function tableName(){
    return 'user_template_tbl';
  }

  public static function getDb(){
    return Module::getInstance()->db;
  }
}

