<?php
//require '../config.php';

/**
* 
*
* $fileUpload = new fileUpload ();
* echo ( !$fileUpload->createSourse('Field_Input_Type_File_Name') ) ? $fileUpload->getError() : (!$fileUpload->upload()) ? $fileUpload->getError() : 'файл/ы загружены'  ;
* 
* $fileUpload =  new fileUpload ('Field_Input_Type_File_Name') ;
* echo ( !$error = $fileUpload->getError() ) ? 'файл загружен' : $fileUpload->getError() ;
* 
* 
* 
*/

class fileUpload{
	protected $_current_file_ext;
	protected $_sourse = array();
	protected $_ERROR;

	public $_rename 		= true;
	public $_allowed_type 	= array('jpg'=>'image/jpeg','png' =>'image/png','gif' =>'image/gif');
	public $_allowed_size 	= 512000;
	public $_field_name;
	public $_upload_dir 	= IMAGES_PATH;
	public $_uploaded 		= array();


	public function __destruct(){}
	public function __construct($field_name = null, $upload_dir = '', $allowed_type = null, $allowed_size = null ){
		$this->_ERROR = new Error();
		if($field_name){
			if( $this->createSourse($field_name, $upload_dir, $allowed_type, $allowed_size) ){
				$this->upload();  
			}
		} 
	}

	public function createSourse ($field_name = null, $upload_dir = '', $allowed_type = null, $allowed_size = null) {

		if(!$field_name ) throw new Exception('не задан обязательный параметр - имя инпута');

		if(!$_FILES || !$_FILES[$field_name]['name']){

			$this->_ERROR->addErrorMessage('нет загружаемых файлов');

		}elseif( (string) ($this->_field_name = $field_name) ) {

			if($_FILES[$this->_field_name]['name']){

				(!$upload_dir) 	 ?: $this->_upload_dir 	 = $upload_dir;
				(!$allowed_type) ?: $this->_allowed_type = $allowed_type;
				(!$allowed_size) ?: $this->_allowed_type = $allowed_size;               

				if( is_array($_FILES[$this->_field_name]['name'] ) ){
					$this->createSourseArray();
				}else{
					$this->_sourse[] = $_FILES['file'];
				}			

			}else{
				$this->_ERROR->addErrorMessage('нет загружаемых файлов');
			}

		}
		if(!$this->_ERROR->getErrorMessage())  return $this->_sourse;    
		return false;
	}

	public function upload () {
		$uploaded = array();
		foreach($this->_sourse as $k=>$file){
			if(!$ext = $this->checkFile($file)) continue;
			$file_name =  $this->_upload_dir;
			$file_name .= ( $this->_rename) ? md5( rand().$file['name'].time() ). '.'.$ext : $file['name'] ;
			if( !move_uploaded_file( $file['tmp_name'], $file_name ) ){
				$this->_ERROR->addErrorMessage('загрузка файла ' .$file['name'].' не удалась'); 
			}else{
				$uploaded[] = $file_name;
			}
		}
		return $this->_uploaded = $uploaded;

	}

	public function checkFile ($file) {
		if($file['error']){
			$this->_ERROR->addErrorMessage( $file['error'] . ' ошибка загрузки файла ' . $file['name'] );
		}
		if($file['size'] > $this->_allowed_size){
			$this->_ERROR->addErrorMessage( 'размер файла ' .number_format($file['size']). '- максимально допустимый размер '. number_format($this->_allowed_size) . ' Kb'); 
		}
		if(!$e = $this->checkMime($file)){
			$this->_ERROR->addErrorMessage( 'файл ' .$file['name']. ' недопустимого типа ');
		}
		if(!$this->_ERROR->getErrorMessage())return $e;
		return false;
	}

	public function checkMime ($file) {
		$fi = finfo_open () ;
		isset($file['tmp_name']) ? $finfo  = finfo_file($fi, $file['tmp_name'] , FILEINFO_MIME_TYPE) : $finfo = null ;
		finfo_close($fi);
		return array_search($finfo, $this->_allowed_type);
	}

	public function createSourseArray () {

		/**
		* @TODO доделать множественную загрузку
		*/

		return $this;
	}

	public function getError ( $prefix = '<div>', $suffix ='</div>') {
		if( $this->_ERROR->getErrorMessage( $prefix, $suffix ) ) return $this->_ERROR->getErrorMessage( $prefix, $suffix ) ;
		return false;
	}
}

/*
if(isset($_POST['submit'])){
	$fileUpload =  new fileUpload ('file') ;
	echo ( !$error = $fileUpload->getError() ) ? 'файл загружен' : $fileUpload->getError() ;    
}
 <!--form action="<?=$_SERVER['PHP_SELF'] ?>" multiple enctype="multipart/form-data" method="post">

	File:<input type="file" multiple accept="image/jpeg, image/png, image/gif"  name="file" />
	<input type="submit" name="submit" value="Upload"  />

</form-->
*/





