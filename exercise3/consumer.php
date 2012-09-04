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

$exchange = new AMQPExchange( $channel );
$exchange->setName( 'logs' );
$exchange->setType( AMQP_EX_TYPE_FANOUT );
$exchange->declare();

$queue = new AMQPQueue( $channel );
$queue->declare();

// Binding key is not important as we are binding to a fanout exchange.
$queue->bind( 'logs', $queue->getName() );

$callback = function( $envelope, $queue ) {
	echo $envelope->getBody(),"\n";
	$queue->ack( $envelope->getDeliveryTag() );
};
$queue->consume( $callback );