<?php
namespace App\Notification;

use Twig\Environment;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="notification")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorMap({
 *  "notification_bug"   = "NotificationBug"
 * })
 */
abstract class Notification {

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var Environment
     */
    protected $renderer;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }
}