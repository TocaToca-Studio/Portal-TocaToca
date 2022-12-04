<?php

/**
 *  Essa classe serve para ler um arquivo de imagem, compactar e redimensionar sem perder a proporção e o aspecto da imagem.
 *  Ela é capaz ler um arquivo da internet, ou local. assim em demanda, você pode redimensionar
 *  a imagem antes mesmo de salvar no disco. ou ainda enviar para o navegador diretamente
 *  também é possivel redimensionar e compactar imagens em demanda, enviando para o navegador sem precisar salvar no disco.
 */
class ImageTool {
    private $image;
    /** aceita o caminho absoluto do arquivo ou o url da imagem na web */
    function __construct($url_or_path) {

        $format = strtolower(pathinfo($url_or_path, PATHINFO_EXTENSION));
        
        try {
            if ($format == "webp") $im = @imagecreatefromwebp($url_or_path);
            elseif ($format == "png") {
                $im = @imagecreatefrompng($url_or_path);
                if (!$im) $im = @imagecreatefromjpeg($url_or_path);
            }elseif ($format == "jpg" || $format == "jpeg"  || $format == "jfif") $im = @imagecreatefromjpeg($url_or_path);
            elseif ($format == "gif") $im = @imagecreatefromgif($url_or_path);
            elseif ($format == "bmp") $im = @imagecreatefrombmp($url_or_path);

            if(!$im) $im = @imagecreatefromstring(file_get_contents($url_or_path));

        } catch (Exception $e) {
        }
        /* tenta ler todos os formatos ate achar um que funcione. porque muitas vezes a informacao da extensao esta errada*/
        if (!$im) $im = @imagecreatefromjpeg($url_or_path);
        if (!$im) $im = @imagecreatefrompng($url_or_path);
        if (!$im) $im = @imagecreatefrombmp($url_or_path);
        if (!$im) $im = @imagecreatefromgif($url_or_path);            
        if (!$im) $im = @imagecreatefromwebp($url_or_path);
        /* se nao encontrou cria uma imagem de erro*/
        if (!$im) {
            // cria uma imagem de 100*30
            $im = imagecreate(150, 20);

            // fundo branco e texto azul
            $bg = imagecolorallocate($im, 255, 255, 255);
            $textcolor = imagecolorallocate($im, 0, 0, 255);

            // escreve a string em cima na esquerda
            imagestring($im, 5, 0, 0, "IMAGE NOT LOADED", $textcolor);
        }
        $this->image=$im;
        //var_dump($im);
        return $this;
    }
    /** funcao para criar uma imagem de erro, caso aconteça algo errado */

    /** redimensiona a imagem sem levar em consideração o aspecto da imagem */
    public function resize($width,$height) {
        $image_resource = imagecreatetruecolor($width, $height);
        imagefill($image_resource, 0, 0, imagecolorallocate($image_resource, 255, 255, 255));  // white background;
        imagecopyresampled($image_resource, $this->image, 0, 0, 0, 0, $width, $height, imagesx($this->image), imagesy($this->image));
        imagedestroy($this->image);
        $this->image=$image_resource;
        return $this;
    }

    /** redimensiona a imagem para a largura máxima definida, porém mantendo o aspecto */
    function resize_max_width($max_width) {
        $max_width=intval($max_width);
        $width = imagesx($this->image);
        $height = imagesy($this->image);

        if($width<=$max_width) return $this;

        $aspect_ratio = $height / $width;

        $this->resize($max_width,$max_width * $aspect_ratio);
        return $this;
    }
    
    /** redimensiona a imagem para a altura máxima definida, porém mantendo o aspecto */
    function resize_max_height($max_height) {
        $max_height=intval($max_height);
        $width = imagesx($this->image);
        $height = imagesy($this->image);

        $aspect_ratio = $height / $width;

        if($height<=$max_height) return $this;

        $this->resize($max_height / $aspect_ratio,$max_height);
        return $this;
    }

    /** redimensiona a imagem para o tamanho máximo definido, porém mantendo o aspecto */
    function resize_max($max_width,$max_height) {
        $width = imagesx($this->image);
        $height = imagesy($this->image);        
        if($width>$max_width) $this->resize_max_width($max_width);
        if($height>$max_height) $this->resize_max_height($max_height);
        return $this;
    }
    /** salva a imagem como jpeg para o caminho especificado */
    function saveJPEG($filename=null,$quality_percent=95) {
        // Convert it to a jpeg file with 70% quality
        imagejpeg($this->image, $filename, $quality_percent);
        imagedestroy($this->image);
    }
    /** envia a imagem para o navegador, e destroy a instancia */
    function send_to_browser($quality_percent= 95) {
        header('Content-type: image/jpg');
        // Convert it to a jpeg and send to browser
          imagejpeg($this->image, NULL, $quality_percent);
        imagedestroy($this->image);
    }
    /** remove a imagem da memoria */
    public function destroy() {imagedestroy($this->image); }
    
}