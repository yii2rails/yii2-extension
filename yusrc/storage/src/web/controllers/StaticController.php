<?php

namespace yubundle\storage\web\controllers;

use App;
use Imagine\Gd\Imagine;
use yubundle\storage\imagine\Image;
use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;
use yii2rails\extension\common\exceptions\AlreadyExistsException;
use yii2rails\extension\web\enums\HttpHeaderEnum;
use yii2rails\extension\yii\helpers\FileHelper;
use yubundle\storage\admin\forms\UploadForm;
use kartik\alert\Alert;
use Yii;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii\web\Controller;
use yubundle\storage\domain\v1\entities\FileEntity;
use yubundle\storage\domain\v1\entities\ServiceEntity;
use yubundle\storage\domain\v1\helpers\StorageHelper;
use yubundle\storage\domain\v1\repositories\ar\FileRepository;

class StaticController extends Controller {

    public function actionIndex()
    {
        $filePath = \Yii::$app->request->getQueryParam('path');
        $isSource = $this->isSource($filePath);
        $thumbSize = StorageHelper::parseThumbDirName($filePath);
        $webRoot = \Yii::getAlias('@webroot');
        $webRootStorage = $webRoot . SL . 'storage';
        if($isSource) {
            try {
                $fileEntity = \App::$domain->storage->static->getFileEntityByFilePath($filePath);
            } catch (NotFoundHttpException $e) {
                return $this->notFound();
            }
            $realPath = $webRoot . SL. $fileEntity->file_path;
        } elseif($thumbSize) {
            $filePathInfo = StorageHelper::parseFilePath($filePath);
            try {
                $serviceEntity = \App::$domain->storage->repositories->service->oneByDir($filePathInfo['profileDir']);
                $query = new Query;
                $query->andWhere($filePathInfo['thumbSize']);
                $query->andWhere(['service_id' => $serviceEntity->id]);
                $thumbEntity = \App::$domain->storage->repositories->serviceThumb->one($query);
            } catch (NotFoundHttpException $e) {
                return $this->notFound();
            }

            $source_path = $filePathInfo['profileDir'] . SL . $filePathInfo['fileName'] . DOT . $filePathInfo['extension'];
            $realPath = $webRootStorage . SL . $filePath;
            FileHelper::createDirectory(dirname($realPath));
            Image::thumbnail($webRootStorage . SL . $source_path, $thumbSize['width'], $thumbSize['height'])
                ->save($realPath, ['quality' => 80]);
        }
		if(empty($realPath)) {
			return $this->notFound();
		}
        $isDownload = \Yii::$app->request->getQueryParam('action') == 'download';
        if($isDownload) {
            return $this->downloadFile($realPath, $fileEntity->file_name);
        }
        return $this->readFile($realPath);
    }

    public function actionFileByHash() {
        $filePath = \Yii::$app->request->getQueryParam('path');
        $isSource = $this->isSource($filePath);
        $thumbSize = StorageHelper::parseThumbDirName($filePath);
        $webRoot = \Yii::getAlias('@webroot');
        $webRootStorage = $webRoot . SL . 'storage';
        try {
            $fileEntity = \App::$domain->storage->static->getFileEntityByFileHash($filePath);
        } catch (NotFoundHttpException $e) {
            return $this->notFound();
        }
        if($isSource) {
            $realPath = $webRoot . SL. $fileEntity->file_path;
        } elseif($thumbSize) {
            $filePathInfo = StorageHelper::parseFilePath($filePath);
            try {
                $serviceEntity = \App::$domain->storage->repositories->service->oneByDir($filePathInfo['profileDir']);
                $query = new Query;
                $query->andWhere($filePathInfo['thumbSize']);
                $query->andWhere(['service_id' => $serviceEntity->id]);
                $thumbEntity = \App::$domain->storage->repositories->serviceThumb->one($query);
            } catch (NotFoundHttpException $e) {
                return $this->notFound();
            }

            $source_path = $filePathInfo['profileDir'] . SL . $filePathInfo['fileName'];
            $realPath = $webRootStorage . SL . $filePath;
            FileHelper::createDirectory(dirname($realPath));
            Image::thumbnail($webRootStorage . SL . $source_path . DOT . $fileEntity->extension, $thumbSize['width'], $thumbSize['height'])
                ->save($realPath, ['quality' => 80]);
        }
        if(empty($realPath)) {
            return $this->notFound();
        }
        $isDownload = \Yii::$app->request->getQueryParam('action') == 'download';
        if($isDownload) {
            return $this->downloadFile($realPath, $fileEntity->file_name);
        }
        if($thumbSize) {
            return $this->readFile($realPath);
        } else {
            return $this->readFile($realPath);
        }
    }

    private function downloadFile($real_path, $fileName) {
        $options['mimeType'] = true;
        return \Yii::$app->response->sendFile($real_path, $fileName, $options);
    }

    private function notFound() {
        \Yii::$app->response->statusCode = 404;
    }

    private function readFile($real_path) {
        $mimeType = FileHelper::getMimeTypeByExtension($real_path);
        \Yii::$app->response->format = yii\web\Response::FORMAT_RAW;
        \Yii::$app->response->headers->add(HttpHeaderEnum::CONTENT_TYPE, $mimeType);
        \Yii::$app->response->data = file_get_contents($real_path);
        return \Yii::$app->response;
    }

    private function isSource(string $filePath) : bool {
        $dir = dirname($filePath);
        $isSource = preg_match('/(\/source)$/i', $dir);
        return $isSource;
    }



}