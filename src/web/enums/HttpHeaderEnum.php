<?php

namespace yii2rails\extension\web\enums;

use yii2rails\extension\enum\base\BaseEnum;

class HttpHeaderEnum extends BaseEnum {
	
	const LINK = 'Link';
	const TOTAL_COUNT = 'X-Pagination-Total-Count';
	const PAGE_COUNT = 'X-Pagination-Page-Count';
	const CURRENT_PAGE = 'X-Pagination-Current-Page';
	const PER_PAGE = 'X-Pagination-Per-Page';
	const TIME_ZONE = 'Time-Zone';
	const CONTENT_TYPE = 'Content-Type';
	const AUTHORIZATION = 'Authorization';
	const ACCESS_TOKEN = 'Access-Token';
	const X_REQUESTED_WITH = 'X-Requested-With';
    const X_ENTITY_ID = 'X-Entity-Id';
	const LANGUAGE = 'Language';
    const X_RUNTIME = 'X-Runtime';
    const X_AGENT_FINGERPRINT = 'X-Agent-Fingerprint';

}
