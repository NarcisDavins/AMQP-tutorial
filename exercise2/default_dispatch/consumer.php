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
$queue->setName( 'work_queue' );
$queue->setFlags( AMQP_DURABLE );
$queue->declare();

$callback = function( $envelope, $queue ) {
	echo 'received message ',$envelope->getBody(),"\n";
	sleep( $envelope->getBody() );

	// Acknowledge the message.
	$queue->ack( $envelope->getDeliveryTag() );
};

$queue->consume( $callback );