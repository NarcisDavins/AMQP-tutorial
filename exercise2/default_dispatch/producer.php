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
$queue->setName( 'work_queue' );
$queue->setFlags( AMQP_DURABLE );
$queue->declare();

$exchange = new AMQPExchange( $channel );
$exchange->publish( 1, 'work_queue' );
$exchange->publish( 6, 'work_queue' );
$exchange->publish( 1, 'work_queue' );
$exchange->publish( 6, 'work_queue' );
$exchange->publish( 2, 'work_queue' );
$exchange->publish( 1, 'work_queue' );