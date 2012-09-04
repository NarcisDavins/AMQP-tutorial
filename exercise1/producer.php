<?php
/**
 * php producer.php
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

$queue = new AMQPQueue( $channel );
$queue->setName( 'hello' );
$queue->declare();

// Use default exchange to send the message.
$exchange = new AMQPExchange( $channel );
$exchange->publish( 'hello world!', 'hello' );