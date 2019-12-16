<?php

namespace yubundle\account\console\controllers;

use Firebase\JWT\JWT;
use Yii;
use yii\base\Model;
use yii2mod\helpers\ArrayHelper;
use yii2rails\domain\behaviors\query\QueryFilter;
use yii2rails\domain\data\Query;
use yii2rails\domain\values\TimeValue;
use yii2rails\extension\common\helpers\time\TimeHelper;
use yii2rails\extension\console\base\Controller;
use yii2rails\extension\console\helpers\input\Enter;
use yii2rails\extension\console\helpers\input\Question;
use yii2rails\extension\console\helpers\input\Select;
use yii2rails\extension\console\helpers\Output;
use yii2rails\extension\enum\enums\TimeEnum;
use yii2rails\extension\jwt\helpers\JwtHelper;

class TokenController extends Controller
{

	public function actionGenerate()
	{
        $post = Enter::form(CreateTokenForm::class);
        $loginEntity = \App::$domain->account->login->oneByLogin($post['login']);

        Output::line();
        Output::line('Selected user: ');
        Output::line();
        Output::arr($loginEntity->toArray(['id', 'login']));

        $answer = Question::confirm('Confirm?');
        if($answer) {
            Output::line();
            $profileEntity = \App::$domain->jwt->profile->oneById('auth');
            $profileEntity->life_time = TimeEnum::SECOND_PER_MINUTE * $post['life_time'];
            $token = \App::$domain->account->token->forge($loginEntity->id, null, $profileEntity);
            Output::line();
            Output::pipe('Generated token');
            Output::autoWrap($token);
            Output::pipe();
            Output::line();
        }
	}

    public function actionInfo()
    {
        /*$profiles = \App::$domain->jwt->profile->all();
        $profiles = ArrayHelper::index($profiles, 'name');
        $profileNames = array_keys($profiles);
        if(count($profileNames) > 1) {
            $profileName = Select::display('Select profile', $profileNames);
            $profileName = ArrayHelper::first($profileName);
        } else {
            $profileName = $profileNames[0];
        }
        $profileEntity = $profiles[$profileName];*/

        $token = Enter::display('JWT token');
        $jwtTokenEntity = JwtHelper::tokenDecode($token);

        $userId = $jwtTokenEntity->payload->subject->id;

        $query = new Query;
        $query->with('person');
        $query->with('assignments');
        $identity = \App::$domain->account->login->oneById($userId, $query);

        Output::arr(ArrayHelper::toArray($identity), 'Identity');
        Output::line();
        Output::arr(ArrayHelper::toArray($identity->person), 'Person');
        Output::line();
        Output::items(ArrayHelper::toArray($identity->roles), 'Roles');
        Output::line();

        $timeValue = new TimeValue($jwtTokenEntity->payload->exp);
        $expireString = $timeValue->getInFormat(TimeValue::FORMAT_WEB);
        $timeZone = TimeHelper::getTimeZone();

        /*$dateTimeZone = new \DateTimeZone($timeZone);
        $date = new \DateTime(null, $dateTimeZone);
        $timeZoneOffset = $dateTimeZone->getOffset($date)/60/60;*/

        Output::arr([
            'Type' => $jwtTokenEntity->header->typ,
            'Algoritm' => $jwtTokenEntity->header->alg,
            'ID' => $jwtTokenEntity->header->kid,
            'Audience' => implode(', ', $jwtTokenEntity->payload->aud),
            'Expire' => $expireString . ' ' . $timeZone,
            'Signature' => JWT::urlsafeB64Encode($jwtTokenEntity->sig),
        ], 'Token');
    }
	
}


class CreateTokenForm extends Model {

    public $login;
    public $life_time = TimeEnum::SECOND_PER_MINUTE * 20;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['login', 'life_time'], 'required'],
            [['life_time'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'life_time' => 'Life time (minute)',
        ];
    }

}
