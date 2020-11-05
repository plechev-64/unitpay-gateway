<?php

function rcl_get_unitpay_children() {

	return array(
		'RclUnitpayMC'			 => __( 'Мобильный платеж' ),
		'RclUnitpayCard'		 => __( 'Пластиковые карты' ),
		'RclUnitpayWebmoney'	 => __( 'WebMoney Z' ),
		'RclUnitpayWebmoneyWmr'	 => __( 'WebMoney R' ),
		'RclUnitpayYandex'		 => __( 'Яндекс.Деньги' ),
		'RclUnitpayQiwi'		 => __( 'Qiwi' ),
		'RclUnitpayPaypal'		 => __( 'PayPal' ),
		'RclUnitpayApplepay'	 => __( 'Apple Pay' ),
		'RclUnitpaySamsungpay'	 => __( 'Samsung Pay' )
	);
}

add_action( 'rcl_payments_gateway_init', 'rcl_gateway_unitpay_init', 10 );
function rcl_gateway_unitpay_init() {

	rcl_gateway_register( 'unitpay', 'Rcl_Unitpay_Gateway' );

	if ( rcl_get_commerce_option( 'unitpay_explode' ) ) {

		if ( $gates = rcl_get_commerce_option( 'payment_gateways' ) ) {
			foreach ( rcl_get_unitpay_children() as $gateId => $name ) {
				if ( ! in_array( $gateId, $gates ) )
					continue;
				rcl_gateway_register( $gateId, $gateId );
			}
		}
	}
}

class Rcl_Unitpay_Gateway extends Rcl_Gateway_Core {

	public $payment_url = '';

	function __construct( $args = [ ] ) {
		parent::__construct( wp_parse_args( $args, array(
			'request'		 => 'unitpay-request',
			'name'			 => rcl_get_commerce_option( 'unitpay_custom_name', 'UnitPay' ),
			'submit'		 => __( 'Оплатить через UnitPay' ),
			'icon'			 => rcl_addon_url( 'icon.jpg', __FILE__ ),
			'handle_forms'	 => rcl_get_commerce_option( 'unitpay_explode' ) ? 1 : 0
		) ) );

		$public_key	 = rcl_get_commerce_option( 'unitpay_public_key' );
		$domain		 = rcl_get_commerce_option( 'unitpay_domain', 'unitpay.ru' );

		$this->payment_url = "https://$domain/pay/$public_key";
	}

	function get_options() {

		return array(
			array(
				'type'			 => 'text',
				'slug'			 => 'unitpay_custom_name',
				'title'			 => __( 'Наименование платежной системы' ),
				'placeholder'	 => 'UnitPay'
			),
			array(
				'type'	 => 'select',
				'slug'	 => 'unitpay_domain',
				'title'	 => __( 'Домен платежной системы' ),
				'values' => array(
					'unitpay.ru'	 => 'unitpay.ru',
					'unitpay.money'	 => 'unitpay.money'
				)
			),
			array(
				'type'	 => 'text',
				'slug'	 => 'unitpay_public_key',
				'title'	 => __( 'PUBLIC KEY' )
			),
			array(
				'type'	 => 'password',
				'slug'	 => 'unitpay_secret_key',
				'title'	 => __( 'SECRET KEY' )
			),
			array(
				'type'		 => 'select',
				'slug'		 => 'unitpay_explode',
				'title'		 => __( 'Разбить форму на отдельные' ),
				'values'	 => array(
					__( 'Нет' ), __( 'Да' )
				),
				'childrens'	 => array(
					1 => array(
						array(
							'type'	 => 'checkbox',
							'slug'	 => 'payment_gateways',
							'title'	 => __( 'Выбрать способы оплаты' ),
							'values' => rcl_get_unitpay_children()
						)
					)
				)
			)
		);
	}

	function get_payment_form_fields( $data ) {

		$secret_key = rcl_get_commerce_option( 'unitpay_secret_key' );

		$fields = array(
			'account'		 => json_encode( array(
				'baggage_data'	 => $data->baggage_data,
				'user_id'		 => $data->user_id,
				'pay_type'		 => $data->pay_type,
				'pay_id'		 => $data->pay_id
			) ),
			'sum'			 => $data->pay_summ,
			'desc'			 => $data->description,
			'currency'		 => $data->currency,
			'customerEmail'	 => get_the_author_meta( 'email', $data->user_id )
		);

		$fields['signature'] = hash( 'sha256', $fields['account'] . '{up}' .
			$fields['currency'] . '{up}' .
			$fields['desc'] . '{up}' .
			$fields['sum'] . '{up}' .
			$secret_key
		);

		if ( $data->pay_type == 1 ) {

			$cashItems = array(
				array(
					"name"	 => __( 'Пополнение личного счета' ),
					"count"	 => 1,
					"price"	 => $data->pay_summ
				)
			);
		} else if ( $data->pay_type == 2 ) {

			$order = rcl_get_order( $data->pay_id );

			if ( $order ) {

				/* $cashItems = array();

				  foreach($order->products as $product){

				  $total = $product->product_price * $product->product_amount;

				  $cashItems[] = array(
				  "name" => get_the_title($product->product_id),
				  "count" => $product->product_amount,
				  "price" => $product->product_price
				  );

				  } */

				$cashItems = array(
					array(
						"name"	 => __( 'Оплата заказа' ) . ' №' . $order->order_id,
						"count"	 => 1,
						"price"	 => $order->order_price
					)
				);
			}
		} else {

			$cashItems = array(
				array(
					"name"	 => $data->description,
					"count"	 => 1,
					"price"	 => $data->pay_summ
				)
			);
		}

		$fields['cashItems'] = base64_encode( json_encode( $cashItems ) );

		return $fields;
	}

	function get_form( $data ) {

		if ( rcl_get_commerce_option( 'unitpay_explode' ) ) {
			if ( rcl_get_commerce_option( 'unitpay_cases' ) ) {
				return false;
			}
		}

		return parent::construct_form( array(
				'action' => $this->payment_url,
				'method' => 'get',
				'fields' => $this->get_payment_form_fields( $data )
			) );
	}

	function result( $data ) {

		$public_key	 = rcl_get_commerce_option( 'unitpay_public_key' );
		$secret_key	 = rcl_get_commerce_option( 'unitpay_secret_key' );

		if ( empty( $_REQUEST['method'] ) || empty( $_REQUEST['params'] ) || ! is_array( $_REQUEST['params'] )
		) {
			echo $this->getResponseError( 'Ошибочный запрос' );
			exit;
		}

		$params		 = wp_unslash( $_REQUEST['params'] );
		$method		 = $_REQUEST['method'];
		$signature	 = $params["signature"];

		ksort( $params );
		unset( $params['sign'] );
		unset( $params['signature'] );
		array_push( $params, $secret_key );
		array_unshift( $params, $method );
		$sign = hash( 'sha256', join( '{up}', $params ) );

		if ( $sign != $signature ) {
			rcl_add_log( 'unitpay-request', 3, 1 );
			rcl_mail_payment_error( $sign );
			echo $this->getResponseError( 'Ошибка проверки подписи' );
			exit;
		}

		$baggage_data = json_decode( $params["account"] );

		if ( $_REQUEST['method'] == 'pay' && ! parent::get_payment( $baggage_data->pay_id ) ) {

			parent::insert_payment( array(
				'pay_id'		 => $baggage_data->pay_id,
				'pay_summ'		 => $params["orderSum"],
				'user_id'		 => $baggage_data->user_id,
				'pay_type'		 => $baggage_data->pay_type,
				'baggage_data'	 => $baggage_data->baggage_data
			) );
		}

		echo $this->getResponseSuccess( 'Запрос успешно обработан' );
		exit;
	}

	function getResponseSuccess( $message ) {
		return json_encode( array(
			"jsonrpc"	 => "2.0",
			"result"	 => array(
				"message" => $message
			),
			'id'		 => 1,
			) );
	}

	function getResponseError( $message ) {
		return json_encode( array(
			"jsonrpc"	 => "2.0",
			"error"		 => array(
				"code"		 => -32000,
				"message"	 => $message
			),
			'id'		 => 1
			) );
	}

}

require_once 'children/children-classes.php';
