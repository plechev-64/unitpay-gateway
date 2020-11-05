<?php

class RclUnitpayMC extends Rcl_Unitpay_Gateway {
	function __construct() {
		parent::__construct( array(
			'name'				 => 'Мобильный платеж',
			'submit'			 => __( 'Оплатить мобильным платежом' ),
			'icon'				 => rcl_addon_url( 'children/icons/mobile.jpg', __FILE__ ),
			'handle_options'	 => 1,
			'handle_forms'		 => 0,
			'handle_activate'	 => 1
		) );

		$this->payment_url .= "/mc";
	}

	function get_options() {
		return false;
	}

}

class RclUnitpayCard extends Rcl_Unitpay_Gateway {
	function __construct() {
		parent::__construct( array(
			'name'				 => 'Банковские карты',
			'submit'			 => __( 'Оплатить банковской картой' ),
			'icon'				 => rcl_addon_url( 'children/icons/card.jpg', __FILE__ ),
			'handle_options'	 => 1,
			'handle_forms'		 => 0,
			'handle_activate'	 => 1
		) );

		$this->payment_url .= "/card";
	}

}

class RclUnitpayWebmoney extends Rcl_Unitpay_Gateway {
	function __construct() {
		parent::__construct( array(
			'name'				 => 'WebMoney WMZ',
			'submit'			 => __( 'Оплатить WebMoney WMZ' ),
			'icon'				 => rcl_addon_url( 'children/icons/webmoney.jpg', __FILE__ ),
			'handle_options'	 => 1,
			'handle_forms'		 => 0,
			'handle_activate'	 => 1
		) );

		$this->payment_url .= "/webmoney";
	}

}

class RclUnitpayWebmoneyWmr extends Rcl_Unitpay_Gateway {
	function __construct() {
		parent::__construct( array(
			'name'				 => 'WebMoney WMR',
			'submit'			 => __( 'Оплатить WebMoney WMR' ),
			'icon'				 => rcl_addon_url( 'children/icons/webmoney.jpg', __FILE__ ),
			'handle_options'	 => 1,
			'handle_forms'		 => 0,
			'handle_activate'	 => 1
		) );

		$this->payment_url .= "/webmoneyWmr";
	}

}

class RclUnitpayYandex extends Rcl_Unitpay_Gateway {
	function __construct() {
		parent::__construct( array(
			'name'				 => 'Яндекс.Деньги',
			'submit'			 => __( 'Оплатить через Яндекс.Деньги' ),
			'icon'				 => rcl_addon_url( 'children/icons/yandex.jpg', __FILE__ ),
			'handle_options'	 => 1,
			'handle_forms'		 => 0,
			'handle_activate'	 => 1
		) );

		$this->payment_url .= "/yandex";
	}

}

class RclUnitpayQiwi extends Rcl_Unitpay_Gateway {
	function __construct() {
		parent::__construct( array(
			'name'				 => 'Qiwi',
			'submit'			 => __( 'Оплатить через Qiwi' ),
			'icon'				 => rcl_addon_url( 'children/icons/qiwi.jpg', __FILE__ ),
			'handle_options'	 => 1,
			'handle_forms'		 => 0,
			'handle_activate'	 => 1
		) );

		$this->payment_url .= "/qiwi";
	}

}

class RclUnitpayPaypal extends Rcl_Unitpay_Gateway {
	function __construct() {
		parent::__construct( array(
			'name'				 => 'PayPal',
			'submit'			 => __( 'Оплатить через PayPal' ),
			'icon'				 => rcl_addon_url( 'children/icons/paypal.jpg', __FILE__ ),
			'handle_options'	 => 1,
			'handle_forms'		 => 0,
			'handle_activate'	 => 1
		) );

		$this->payment_url .= "/paypal";
	}

}

class RclUnitpayApplepay extends Rcl_Unitpay_Gateway {
	function __construct() {
		parent::__construct( array(
			'name'				 => 'Apple Pay',
			'submit'			 => __( 'Оплатить через Apple Pay' ),
			'icon'				 => rcl_addon_url( 'children/icons/applepay.jpg', __FILE__ ),
			'handle_options'	 => 1,
			'handle_forms'		 => 0,
			'handle_activate'	 => 1
		) );

		$this->payment_url .= "/applepay";
	}

}

class RclUnitpaySamsungpay extends Rcl_Unitpay_Gateway {
	function __construct() {
		parent::__construct( array(
			'name'				 => 'Samsung Pay',
			'submit'			 => __( 'Оплатить через Samsung Pay' ),
			'icon'				 => rcl_addon_url( 'children/icons/samsungpay.jpg', __FILE__ ),
			'handle_options'	 => 1,
			'handle_forms'		 => 0,
			'handle_activate'	 => 1
		) );

		$this->payment_url .= "/samsungpay";
	}

}
