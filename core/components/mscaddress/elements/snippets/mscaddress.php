<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var mscAddress $msca */
$msca = $modx->getService('mscAddress', 'mscAddress', MODX_CORE_PATH . 'components/mscaddress/model/', $scriptProperties);
if (!$msca) {
    return 'Could not load mscAddress class!';
}
if (!$modx->user->isAuthenticated($modx->context->key)) {
	return '';
}

$hash = md5(http_build_query($scriptProperties));
$msca->initialize($modx->context->key, array('hash_key' => $hash));
// Save settings to user`s session
$msca->session[$hash] = $scriptProperties;

/** @var pdoFetch $pdoFetch */
if (!$modx->loadClass('pdofetch', MODX_CORE_PATH . 'components/pdotools/model/pdotools/', false, true)) {
    return false;
}
$pdoFetch = new pdoFetch($modx, $scriptProperties);
$pdoFetch->addTime('pdoTools loaded.');

$tpl = $modx->getOption('tpl', $scriptProperties, 'tpl.mscaAddresses');
$tplForm = $modx->getOption('tplForm', $scriptProperties, 'tpl.mscaForm');
$selected = $modx->getOption('selected', $scriptProperties, '');

$where = array(
    'msCustomerAddress.user_id' => $modx->user->get('id'),
);
$leftJoin = array();
$select = array(
    'msCustomerAddress' => $modx->getSelectColumns('msCustomerAddress', 'msCustomerAddress', '', array('user_id'), true),
);

// Add user parameters
foreach (array('where', 'leftJoin', 'select') as $v) {
    if (!empty($scriptProperties[$v])) {
        $tmp = $scriptProperties[$v];
        if (!is_array($tmp)) {
            $tmp = json_decode($tmp, true);
        }
        if (is_array($tmp)) {
            $$v = array_merge($$v, $tmp);
        }
    }
    unset($scriptProperties[$v]);
}
$pdoFetch->addTime('Conditions prepared');

$default = array(
    'class' => 'msCustomerAddress',
    'where' => $where,
    'leftJoin' => $leftJoin,
    'select' => $select,
    'sortby' => 'msCustomerAddress.rank',
    'sortdir' => 'ASC',
    'groupby' => 'msCustomerAddress.id',
    'limit' => 0,
    'return' => 'data',
    'nestedChunkPrefix' => 'msca_',
);
// Merge all properties and run!
$pdoFetch->setConfig(array_merge($default, $scriptProperties), false);

$addresses = $pdoFetch->run();

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
if (!$isAjax && isset($_REQUEST['msca_action']) && (in_array($_REQUEST['msca_action'], ['address/add','address/edit','address/save']))){
	$form = $msca->address->get(@$_REQUEST['id'], @$_REQUEST)['data']['html'];
}
else
	$form = '';

$output = $pdoFetch->getChunk($tpl, array(
    'addresses' => $addresses,
    'form' => $form,
    'selected' => $selected,
));

if ($modx->user->hasSessionContext('mgr') && !empty($showLog)) {
    $output .= '<pre class="mscAddressLog">' . print_r($pdoFetch->getTime(), true) . '</pre>';
}

if (!empty($toPlaceholder)) {
    $modx->setPlaceholder($toPlaceholder, $output);
} else {
    return $output;
}