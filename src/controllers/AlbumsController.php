<?php

namespace Controllers;

use Psr\Container\ContainerInterface;
use \Slim\Http\Request;
use \Slim\Http\Response;
use Handlers\AlbumsHandler;
use Templates\AlbumsTemplate;
use Templates\AlbumTemplate;

class AlbumsController extends Controller
{
    protected $container;
    protected $albumsHandler;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->albumsHandler = new AlbumsHandler($this->container->get('db'));
    }

    public function getAlbum(Request $request, Response $response, $args)
    {
        $albumId = $args['albumId'];
        try {
            $album = $this->albumsHandler->getAlbum($albumId);
        } catch (\Exception $e) {
            $this->showError($response, $e->getMessage(), $e->getCode());
        }
        $albumTemplate = new AlbumTemplate($album);
        $response = $response->withJson($albumTemplate->getArray(), 200);
        return $response;
    }

    public function getAlbums(Request $request, Response $response, $args)
    {
        try {
            $albums = $this->albumsHandler->getAlbums();
        } catch (\Exception $e) {
            $this->showError($response, $e->getMessage(), $e->getCode());
        }
        $albumsTemplate = new AlbumsTemplate($albums);
        $response = $response->withJson($albumsTemplate->getArray(), 200);
        return $response;
    }

    public function postAlbum(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        try {
            $album = $this->albumsHandler->insertAlbum($body);
        } catch (\Exception $e) {
            return $this->showError($response, $e->getMessage(), $e->getCode());
        }
        $albumTemplate = new AlbumTemplate($album);
        $response = $response->withJson($albumTemplate->getArray(), 200);
        return $response;
    }

    public function putAlbum(Request $request, Response $response, $args)
    {
        $albumId = $args['albumId'];
        $body = $request->getParsedBody();
        try {
            $album = $this->albumsHandler->updateAlbum($albumId, $body);
        } catch (\Exception $e) {
            return $this->showError($response, $e->getMessage(), $e->getCode());
        }
        $albumTemplate = new AlbumTemplate($album);
        $response = $response->withJson($albumTemplate->getArray(), 200);
        return $response;
    }
}