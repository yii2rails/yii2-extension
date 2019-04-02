<?php

namespace yii2rails\extension\web\enums;

use yii2rails\extension\enum\base\BaseEnum;

class HttpHeaderEnum extends BaseEnum {
	
	const LINK = 'link';
	const TOTAL_COUNT = 'x-pagination-total-count';
	const PAGE_COUNT = 'x-pagination-page-count';
	const CURRENT_PAGE = 'x-pagination-current-page';
	const PER_PAGE = 'x-pagination-per-page';
	const TIME_ZONE = 'time-zone';
	const CONTENT_TYPE = 'content-type';
	const AUTHORIZATION = 'authorization';
	const ACCESS_TOKEN = 'access-token';
	const X_REQUESTED_WITH = 'x-requested-with';
    const X_ENTITY_ID = 'x-entity-id';
	const LANGUAGE = 'language';

}
