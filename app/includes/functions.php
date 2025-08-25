<?php
function qs($params) {
    // 현재 GET 쿼리 유지하면서 필요한 값만 교체
    $query = array_merge($_GET, $params);
    return http_build_query($query);
}
