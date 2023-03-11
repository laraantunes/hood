<?php
/**
 * 2018 Hood Framework
 */

namespace Hood\Toolbox;
/**
 * Manager for Files and Folders
 */
class FileManager {

	/**
	 * Path for current directory
	 * @var string
	 */
	protected $path;
	/**
	 * Files of the current directory
	 * @var array
	 */
	protected $files;
	/**
	 * Folders of the current directory
	 * @var array
	 */
	protected $folders;

	/**
	 * Constructor of the file
	 * @param string $path Path to load
	 * @param boolean $load (Optional) If true, loads the files and folders as soon as object is created
	 */
	public function __construct ($path=null, $load=true)
	{
		if ($path) {
			$this->setPath($path);
			if ($load) {
				$this->loadPath();
			}
		}
	}

	/**
	 * Changes the current directory. Calls loadPath() after the change
	 * @param string $path Path that'll be loaded
	 * @param boolean $reload If true, reloads the path
	 * @return object The current object
	 */
	public function setPath($path, $reload = true)
	{
		if (substr($path, -1, 1) != '/') {
			$path .= "/";
		}
		$this->path = $path;

		if ($reload) {
			$this->loadPath();
		}

		return $this;
	}

	/**
	 * Returns the current path
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * Sets the files array
	 * @param array $files
	 * @return object The current object
	 */
	public function setFiles($files)
	{
		$this->files = $files;
		return $this;
	}

	/**
	 * Returns the array files
	 * @param string $extension If present, returns only the files with the extension informed
	 * @param string $returFullPath Returns the full path of the files
	 * @return array
	 */
	public function getFiles($extension=null, $returFullPath = false)
	{
	   if ($extension){
			$files = array();
			if($this->files){
				foreach($this->files as $file){
					if(strtoupper(substr($file, strlen($file) - strlen($extension))) === strtoupper($extension)){
						$files[] = $file;
					}
				}
			}
			return $files;
		}
		if($returFullPath && $this->files){
			$files = array();
			foreach($this->files as $ind => $file){
				$files[$ind] = realpath($this->path.$file);
			}
			return $files;
		}
		return $this->files;
	}

	/**
	 * Sets the $folders array
	 * @param array $folders
	 * @return object The current object
	 */
	public function setFolders($folders) {
		$this->folders = $folders;
		return $this;
	}

	/**
	 * Returns the $folders array
	 * @return array
	 */
	public function getFolders() {
		return $this->folders;
	}

	/**
	 * Loads / Reloads the directory
	 */
	public function loadPath()
	{
		$pointer = opendir($this->path);
		while ($nome_itens = readdir($pointer)) {
			$itens[] = $nome_itens;
		}
		sort($itens);
		unset($this->files);
		unset($this->folders);

		foreach ($itens as $listar) {
			if ($listar != ".") {
				if (file_exists($this->path . $listar) && is_file($this->path . $listar)) {
					$this->files[] = $listar;
				} else {
					$this->folders[] = $listar;
				}
			}
		}
	}

	/**
	 * Check if file exists on the current path
	 * @param string $filename The filename
	 * @return boolean
	 */
	public function fileExists($filename)
	{
		return file_exists($this->path . $filename);
	}

	/**
	 * Loads the contents of the file
	 * @param string $filename Filename to load
	 * @return boolean|string
	 */
	public function loadFile($filename)
	{
		if (file_exists($this->path . $filename)) {
			$file = fopen($this->path . $filename,"r");
			$s = fread($file,filesize($this->path . $filename));
			fclose($file);
			return $s;
		} else {
			return false;
		}
	}

	/**
	 * Write on a filename - Creates if no exists
	 * @param string $filename The filename
	 * @param string $content The content
	 * @param string $mode The file access mode
	 * @return boolean
	 */
	public function saveFile($filename, $content, $mode = "w")
	{
		$f = fopen($this->path . $filename, $mode);
		if (flock($f, LOCK_EX)) { // Bloqueia o arquivo para evitar acesso simultaneo
			fwrite($f, $content);
			flock($f, LOCK_UN); // libera o lock
		} else {
			return false;
		}
		return fclose($f);
	}

	/**
	 * Deletes a file
	 * @param string $filename Filename to delete
	 * @return boolean
	 */
	public function deleteFile($filename)
	{
		if (file_exists($this->path . $filename)) {
			return unlink($this->path . $filename);
		} else {
			return false;
		}
	}

	/**
	 * Renames a file or a folder
	 * @param string $name Name of the file or folder
	 * @param string $newName New name of the file of folder
	 * @return boolean
	 */
	public function rename($name, $newName)
	{
		if (file_exists($this->path . $name)) {
			return rename($this->path . $name, $this->path . $newName);
		} else {
			return false;
		}
	}

	/**
	 * Moves a file or a folder
	 * @param string $name Name of the file or folder
	 * @param string $newPath New path of the item - Can be outside $this->path
	 * @return boolean
	 */
	public function moveFile($name, $newPath)
	{
		if (substr($newPath, -1, 1) != '/') {
			$newPath .= "/";
		}
		if (file_exists($this->path . $name)) {
			rename($this->path . $filename, $newPath . $filename);
		} else {
			return false;
		}
	}

	/**
	 * Search a file on current directory
	 * @param string $pattern Search pattern
	 * @param int $flags (Optional) Flags for search as seen on PHP's glob() function:
	 * GLOB_MARK - Adds a slash to each directory returned
	 * GLOB_NOSORT - Return files as they appear in the directory (no sorting). When this flag is not used, the pathnames are sorted alphabetically
	 * GLOB_NOCHECK - Return the search pattern if no files matching it were found
	 * GLOB_NOESCAPE - Backslashes do not quote metacharacters
	 * GLOB_BRACE - Expands {a,b,c} to match 'a', 'b', or 'c'
	 * GLOB_ONLYDIR - Return only directory entries which match the pattern
	 * GLOB_ERR - Stop on read errors (like unreadable directories), by default errors are ignored.
	 * @return array
	 */
	public function findFiles($pattern, $flags = null)
	{
		if($flags){
			return glob($this->path . $pattern, $flags);
		}else{
			return glob($this->path . $pattern);
		}
	}

	/**
	 * Creates a folder on the current directory
	 * @param string $foldername Foldername to create
	 * @param int $mode (Optional) Mode (permissions) to the folder
	 * @return boolean
	 */
	public function createFolder($foldername, $mode = 0777)
	{
		return mkdir($this->path . $foldername, $mode);
	}

	/**
	 * Changes the permissions of the folder
	 * @param string $foldername Nome da pasta a ser modificada
	 * @param int $mode Modo (permissÃµes) de acesso Ã  pasta
	 * @return boolean
	 */
	public function changePermissionFolder($foldername, $mode){
		return chmod($this->path . $foldername, $mode);
	}

	/**
	 * MÃ©todo para limpar e/ou excluir um diretÃ³rio
	 * @param string $foldername Diretorio a excluir
	 * @param boolean $empty Indica que o mÃ©todo deve apenas limpar o diretÃ³rio,
	 * sem apagÃ¡-lo
	 * @return boolean
	 */
	public function deleteFolder($foldername, $empty = false) {
		$foldername = $this->path . $foldername;

		if (substr($foldername, -1) == "/") {
			$foldername = substr($foldername, 0, -1);
		}

		if (!file_exists($foldername) || !is_dir($foldername)) {
			return false;
		} elseif (!is_readable($foldername)) {
			return false;
		} else {
			$directoryHandle = opendir($foldername);

			while ($contents = readdir($directoryHandle)) {
				if ($contents != '.' && $contents != '..') {
					$path = $foldername . "/" . $contents;

					if (is_dir($path)) {
						$path = str_replace($this->path, "", $path);
						$this->deleteFolder($path);
					} else {
						unlink($path);
					}
				}
			}

			closedir($directoryHandle);

			if ($empty == false) {
				if (!rmdir($foldername)) {
					return false;
				}
			}

			return true;
		}
	}

	/**
	 * Carrega um arquivo JSON e jÃ¡ retorna os dados como array
	 * @param string $filename Nome do arquivo a carregar
	 * @return array
	 */
	public function loadJsonFile($filename) {
		$s = $this->loadFile($filename);
		return \NyuFormat::jsonFormat($s, "out");
	}

	/**
	 * Salva um conteÃºdo em um arquivo JSON, convertendo o conteÃºdo antes de
	 * gravar, se for um array
	 * @param string $filename Nome do arquivo a ser gravado
	 * @param array|string $content ConteÃºdo a ser gravado
	 * @return boolean
	 */
	public function saveJsonFile($filename, $content) {
		$f = fopen($this->path . $filename, 'w');
		fwrite($f, \NyuFormat::jsonFormat($content, "in"));
		return fclose($f);
	}

	/**
	 * LÃª o conteÃºdo de um arquivo CSV do diretÃ³rio e retorna em formato array
	 * @param string $filename Nome do arquivo
	 * @return array|boolean
	 */
	public function loadCsvFile($filename){
		if (file_exists($this->path . $filename)) {
			$f = fopen($this->path . $filename,'r');
			while ( ($data = fgetcsv($f) ) !== FALSE ) {
				$a[] = $data;
			}
		} else {
			return false;
		}
		fclose($f);
		return $a;
	}

	/**
	 * Cria uma funÃ§Ã£o de autoload a partir da pasta informada
	 * A pasta informada deve estar dentro da pasta informada no atributo
	 * $path do objeto atual.
	 * @param string $path Caminho da pasta a carregar as classes
	 */
	public function autoloadFolder($path){
		$path = $this->path . $path;
		if (substr($path, -1, 1) != '/') {
			$path .= "/";
		}
		$func = create_function('$a','$path = "'.$path.'";'
				. 'if(file_exists($path.str_replace("\\\","/",$a).".class.php")){'
					. 'require_once($path.str_replace("\\\","/",$a).".class.php");'
				. '}'
				. 'elseif(file_exists($path.str_replace("\\\","/",$a).".trait.php")){'
					. 'require_once($path.str_replace("\\\","/",$a).".trait.php");'
				. '}'
				. 'elseif(file_exists($path.str_replace("\\\","/",$a).".interface.php")){'
					. 'require_once($path.str_replace("\\\","/",$a).".interface.php");'
				. '}'
				);
		spl_autoload_register($func);
	}

	/**
	 * MÃ©todo utilizado para gerar csv a partir de um array e fazer download do arquivo
	 * @param array $input_array array a processar
	 * @param string $output_file_name nome do arquivo de destino
	 * @param string $delimiter delimitador das colunas no arquivo csv
	 */
	public static function downloadCsvArray($input_array, $output_file_name, $delimiter){
		/** open raw memory as file, no need for temp files */
		$temp_memory = fopen('php://memory', 'w');
		/** loop through array */
		foreach ($input_array as $line) {
			if(is_object($line)){
				$line = get_object_vars($line);
			}
			/** default php csv handler **/
			fputcsv($temp_memory, $line, $delimiter);
		}
		/** rewrind the "file" with the csv lines **/
		fseek($temp_memory, 0);
		/** modify header to be downloadable csv file **/
		header('Content-Type: application/csv');
		header('Content-Disposition: attachement; filename="' . $output_file_name . '";');
		/** Send file to browser for download */
		fpassthru($temp_memory);
	}

}
