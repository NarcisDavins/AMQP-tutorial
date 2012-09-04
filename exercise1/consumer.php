<?php
/**
 * php consumer.php
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

$callback = function( $envelope ) {
	echo $envelope->getBody(), "\n";

	// Exit AMQPQueue::consume after each message received.
	return false;
};

$queue->consume( $callback );