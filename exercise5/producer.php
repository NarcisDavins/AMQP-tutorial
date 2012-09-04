<?php
/**
 * php producer.php <MESSAGE> <ROUTING_KEY>
 */
$connection = new AMQPConnection( array(
	'host' => '127.0.0.1',
	'port' => 5672,
	'login' => 'guest',
	'password' => 'guest',
	'vhost' => '/'
) );
$connection->connect();
$channel = new AMQPChannel( $connection );

$exchange = new AMQPExchange( $channel );
$exchange->setName( 'logs_topic' );
$exchange->setType( AMQP_EX_TYPE_TOPIC );
$exchange->declare();

$exchange->publish( $argv[1], $argv[2] );