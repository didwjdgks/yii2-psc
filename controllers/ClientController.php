<?php
namespace psc\controllers;

use Yii;
use yii\web\Response;
use yii\db\Query;

use Phar;
use PharData;

class ClientController extends \yii\web\Controller
{
  public function actionIndex(){
    Yii::$app->response->format=Response::FORMAT_JSON;

    $row=(new Query())->from('user_template_tbl')->select('user_id,template_name')
      ->orderBy('update_datel desc')
      ->limit(1)
      ->one($this->module->db);

    $template_dir='/home/ssp/app/frontend/web/user/'.$row['user_id'].'/'.$row['template_name'];
    $tar_dir=Yii::getAlias('@runtime').'/templates';
    @mkdir($tar_dir,0777);

    $phar_file=$tar_dir.'/'.$row['user_id'].'_'.$row['template_name'].'.tar';
    //@unlink($phar_file);
    $phar=new PharData($phar_file);
    $phar->buildFromDirectory($template_dir);
    //$phar->compress(Phar::GZ);

    $hash=hash_file('SHA384',$phar_file);

    return [
      'user_id'=>$row['user_id'],
      'template_name'=>$row['template_name'],
      'hash_file'=>$hash,
      'filename'=>$row['user_id'].'_'.$row['template_name'].'.tar',
    ];
  }

  public function actionDownload(){
    $params=Yii::$app->request->get();

    $file_path=Yii::getAlias('@runtime').'/templates/'.$params['f'];

    return Yii::$app->response->sendFile($file_path);
  }
}

