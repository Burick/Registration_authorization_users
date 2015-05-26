<?php


class Error{

	protected $_error_mesage = null;

	public function __destruct(){}
	public function __construct(){}

	public function addErrorMessage ($message = '', $prefix = '<p>', $suffix = '</p>') {
		if(!$message) return '';
		//$this->_error_mesage .= $prefix.$message.$suffix;
		return $this->_error_mesage .= $prefix.$message.$suffix;
	}

	public function getErrorMessage ( $prefix = '<div>', $suffix ='</div>' ) {
		return ($this->_error_mesage) ? $prefix.$this->_error_mesage.$suffix : '';
	}

}