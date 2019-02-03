<?php

namespace yii2lab\extension\widget\helpers;

use yii2lab\extension\package\domain\entities\ProviderEntity;
use yii2module\vendor\domain\entities\RepoEntity;

class WidgetHelper {

    public static function renderTemplateByRepo(RepoEntity $repoEntity, $name) {
        $url = self::renderTemplate($repoEntity->group->provider->url_templates[$name], $repoEntity->toArray());
        $url = $repoEntity->group->provider->host . SL . $url;
        return $url;
    }
	
	
	public static function renderTemplate($template, $config) {
		$configPrepared = self::prepareTemplateConfig($config);
		$html = strtr($template, $configPrepared);
		return $html;
	}
	
	private static function prepareTemplateConfig($config) {
		$configPrepared = [];
		foreach($config as $key => $value) {
			$keyPrepared = '{' . $key . '}';
			$configPrepared[$keyPrepared] = $value;
		}
		return $configPrepared;
	}
	
	
}
