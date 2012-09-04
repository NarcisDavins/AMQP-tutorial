<?php
/**
 * php consumer.php <binding_key1> [<binding_key2> [...]]
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
$exchange->setName( 'logs_direct' );
$exchange->setType( AMQP_EX_TYPE_DIRECT );
$exchange->declare();

$queue = new AMQPQueue( $channel );
$queue->declare();
$routing_keys = array_slice( $argv, 1 );
foreach ( $routing_keys as $key )
{
	$queue->bind( 'logs_direct', $key );
}

$callback = function( $envelope, $queue ) {
	echo $envelope->getBody(),"\n";
	$queue->ack( $envelope->getDeliveryTag() );
};
$queue->consume( $callback );