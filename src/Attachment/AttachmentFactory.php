<?php
namespace Czim\Paperclip\Attachment;

use Czim\FileHandling\Contracts\Handler\FileHandlerInterface;
use Czim\FileHandling\Contracts\Storage\PathHelperInterface;
use Czim\Paperclip\Contracts\AttachableInterface;
use Czim\Paperclip\Contracts\AttachmentFactoryInterface;
use Czim\Paperclip\Contracts\AttachmentInterface;
use Czim\Paperclip\Contracts\FileHandlerFactoryInterface;
use Czim\Paperclip\Contracts\Path\InterpolatorInterface;

class AttachmentFactory implements AttachmentFactoryInterface
{

    /**
     * @param AttachableInterface $instance
     * @param string              $name
     * @param array               $config
     * @return AttachmentInterface
     */
    public function create(AttachableInterface $instance, $name, array $config = [])
    {
        $attachment = new Attachment;

        $attachment->setInstance($instance);
        $attachment->setName($name);
        $attachment->setConfig($config);
        $attachment->setInterpolator($this->getInterpolator());
        $attachment->setPathHelper($this->getPathHelper());

        $disk = array_get($config, 'storage');
        $attachment->setHandler($this->getHandler($disk));

        return $attachment;
    }

    /**
     * @param string|null $disk
     * @return FileHandlerInterface
     */
    protected function getHandler($disk = null)
    {
        /** @var FileHandlerFactoryInterface $factory */
        $factory = app(FileHandlerFactoryInterface::class);

        return $factory->create($disk);
    }

    /**
     * @return InterpolatorInterface
     */
    protected function getInterpolator()
    {
        return app(InterpolatorInterface::class);
    }

    /**
     * @return PathHelperInterface
     */
    protected function getPathHelper()
    {
        return app(PathHelperInterface::class);
    }

}
