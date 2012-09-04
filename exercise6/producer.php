<?php
/**
 * php producer.php <REQUEST_INTEGER_IN_FIBBONACCI_SERIE>
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

$rpc_queue = new AMQPQueue( $channel );
$rpc_queue->setName( 'rpc_queue' );
$rpc_queue->declare();

// Create callback queue.
$callback_queue = new AMQPQueue( $channel );
$callback_queue->setFlags( AMQP_EXCLUSIVE );
$callback_queue->declare();

$exchange = new AMQPExchange( $channel );
$exchange->publish(
	$argv[1],
	$rpc_queue->getName(),
	AMQP_NOPARAM,
	array( 'reply_to' => $callback_queue->getName() )
);

$consume = function($envelope, $queue){
	echo 'Fibbonacci number is:', $envelope->getBody(), "\n";
	$queue->ack( $envelope->getDeliveryTag() );

	// We are just expecting one response, so we can exit consume method.
	return false;
};

$callback_queue->consume( $consume );