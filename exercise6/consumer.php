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

$rpc_queue = new AMQPQueue( $channel );
$rpc_queue->setName( 'rpc_queue' );
$rpc_queue->declare();

$fib = function( $n ) use ( &$fib )
{
	if ( $n < 2 ) return $n;
	return $fib( $n-1 ) + $fib( $n-2 );
};

// Use default exchange to send response messages.
$exchange = new AMQPExchange( $channel );

$callback = function( $envelope, $queue ) use ( $exchange, $fib ){
	echo 'Received request:', $envelope->getBody(),"\n";
	$fib_number = $fib( $envelope->getBody() );

	$exchange->publish( $fib_number, $envelope->getReplyTo() );
	$queue->ack( $envelope->getDeliveryTag() );
};
$rpc_queue->consume( $callback );