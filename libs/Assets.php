<?php
use function Env\env;

class Assets
{

    private $assets = [];
    private $mode = false;
    private $distName;

    public function __construct($file, $distName)
    {
        if (file_exists($file)) {
            $this->assets = (array)json_decode(file_get_contents($file));
        }

        $env_files = file_exists(get_template_directory() . '/.env.local')
            ? ['.env', '.env.local']
            : ['.env'];

        $dotenv = Dotenv\Dotenv::createUnsafeImmutable(get_template_directory(), $env_files, false);

        if (file_exists(get_template_directory() . '/.env')) {
            $dotenv->load();
        }

        $this->mode = env('MODE');
        $this->distName = $distName;
    }

    public function getUrl($name)
    {

        if (!empty($this->assets[$name])) {
            return $this->assets[$name];
        }

        return null;
    }

    public function enqueueStyle($name, $async = false, $src = null)
    {
        $port = env('PORT');
        $publicPath = env('PUBLIC_PATH');
        $assetsDir = env('ASSETS_DIR');
        $themeName = str_replace( '%2F', '/', rawurlencode( get_template() ) );

        if (empty($src)) {
            if (!empty($this->assets[$name])) {

                if ($this->mode === 'development') {
                    $src = "http://localhost:" . $port . $publicPath . $themeName . "/" . $assetsDir . "/" . $this->assets[$name];
                } else {
                    $src = get_theme_file_uri("$this->distName/" . $this->assets[$name]);
                }
            }
        } else {
            $path = get_theme_file_path("$this->distName/" . $src);
        }

        if ($async) {
            $media = "media='print' onload='this.media=\"screen\"'";
        } else {
            $media = "media='all'";
        }

        $line = "<link rel='stylesheet' id='$name' href='$src' type='text/css' $media>";

        add_action('wp_head', function () use ($line) {
            echo $line;
        });
    }

    public function enqueueScript($name, $async = false, $vars = null, $src = null)
    {
        $port = env('PORT');
        $publicPath = env('PUBLIC_PATH');
        $assetsDir = env('ASSETS_DIR');
        $themeName = str_replace( '%2F', '/', rawurlencode( get_template() ) );

        if (empty($src)) {

            if (!empty($this->assets[$name])) {
                if ($this->mode === 'development') {
                    $src = "http://localhost:" . $port . $publicPath . $themeName . "/" . $assetsDir . "/" . $this->assets[$name];
                } else {
                    $src = get_theme_file_uri("$assetsDir/" . $this->assets[$name]);
                }
            }
        } else {
            $path = get_theme_file_path("$assetsDir/" . $src);
        }

        $defer = "";

        if ($async) {
            $defer = "defer";
        }
        $params = null;

        if ($vars) {
            $params =  "<script type='text/javascript'>";

            foreach ($vars as $key => $var) {
                $params .= 'var ' . $key . '=' . json_encode($var);
            }

            $params .= "</script>";
        }

        $line = "<script id='$name' src='$src' type='text/javascript' $defer></script>";

        add_action('wp_footer', function () use ($line, $params) {
            echo $params;
            echo $line;
        });
    }
}
