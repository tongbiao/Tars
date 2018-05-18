--TEST--
map: vector in map with key string

--SKIPIF--
<?php require __DIR__ . "/../include/skipif.inc"; ?>
--INI--
assert.active=1
assert.warning=1
assert.bail=0
assert.quiet_eval=0

--FILE--
<?php
require_once __DIR__ . "/../include/config.inc";

$shorts = ["test1","test2"];
$vecs = new \TARS_VECTOR(\TARS::STRING);
$vecs->push_back("test1");
$vecs->push_back("test2");
$map = new \TARS_MAP(\TARS::STRING, new \TARS_VECTOR(\TARS::STRING), 1);
$map->pushBack(['key' => 'testMap', 'value' => $vecs]);
$buf = \TUPAPI::putMap("map",$map);

$encodeBufs['map'] = $buf;

$requestBuf = \TUPAPI::encode($iVersion, $iRequestId, $servantName, $funcName, $cPacketType, $iMessageType, $iTimeout, $contexts,$statuses,$encodeBufs);

$decodeRet = \TUPAPI::decode($requestBuf);
if($decodeRet['status'] !== 0) {
    echo "error";
} else {
    $respBuf = $decodeRet['buf'];

    $map = new \TARS_MAP(\TARS::STRING, new \TARS_VECTOR(\TARS::STRING), 1);
    $out = \TUPAPI::getMap("map", $map, $respBuf);

    $data = [['key' => 'testMap', 'value' => ["test1","test2"]]];

    assert($data,$out);
    echo "success";
}

?>
--EXPECT--
success