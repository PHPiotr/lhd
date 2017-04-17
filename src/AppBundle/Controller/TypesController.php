<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TypesController extends Controller
{

    /**
     * @Route("/van-mpv-types", name="types")
     * @Template()
     */
    public function indexAction()
    {
        $types = [
            [
                'title' => 'Panel Van',
                'image' => 'panel_van.jpg',
                'description' => '<p>Delivery van without side windows or rear seats</p>',
            ],
            [
                'title' => 'Half-panel',
                'image' => 'half_panel.jpg',
                'description' => '<p>Van with side windows only in the front half of the cargo area and one row of removable rear seats</p>',
            ],
            [
                'title' => 'Kombi',
                'image' => 'kombi.jpg',
                'description' => '<p>This is the entry level people mover in the Transporter range. It is available with every internal combustion engine in the T5 range, and in all roof heights and wheelbases. The Kombi can seat four to eleven people. It is equipped with basic features such as rubber flooring, heater for driver\'s compartment, and side panel trim and headlining in the driver\'s compartment. Optional features include central locking, air conditioning for front and rear compartments, electrically controlled and heated mirrors, sliding windows, Electronic Stability Programme (ESP), side and curtain airbags, cruise control, electric windows, sunroof, and an electric sliding door.</p>',
            ],
            [
                'title' => 'Shuttle',
                'image' => 'shuttle.jpg',
                'description' => '<p>This is the next level up, is only available in SWB and LWB, with the full engine range, but is limited to the standard roof height. The Shuttle seats seven to eleven people. Standard features are moulded trim, a second heater, sun blinds for the passenger compartment, and a sliding window on the left hand side only. Optional extras over the Kombi include carpeting and an \'Appearance Package\', which includes colour-coded bumpers, double folding rear three-seater bench seat, and a luggage compartment light.</p>',
            ],
            [
                'title' => 'Caravelle',
                'image' => 'caravelle.jpg',
                'description' => '<p>This includes most of the Kombi and Shuttle features already standard, plus ESP, Anti-lock Braking System (ABS), Anti-Slip Regulation (ASR – more commonly known as traction control system), passenger\'s seat with adjustable lumbar support, air conditioning, electrically adjustable and heated mirrors, and armrests for front-seat passenger and driver. Optional features include Automatic Tailgate Power Closing system, and multi-disc CD changer. The Caravelle is only available in SWB or LWB, with a maximum of ten seats.</p>',
            ],
            [
                'title' => 'Multivan',
                'image' => 'multivan.jpg',
                'description' => '<p>This is the range-topping people mover based on the T5 platform. Available with six or seven seats, it has a unique rail feature in which seats can slide forward and backward into any configuration. A wide range of accessories are available, like tables and refrigerators which fit into the rails to be secured or movable if necessary. The Multivan has all safety features as standard such as ABS, ESP, ASR, and front, side, and curtain airbags. The Multivan is sold under the Caravelle nameplate in the UK.</p>',
            ],
            [
                'title' => 'Full Caddy line-up',
                'image' => 'caddy.jpg',
                'description' => '<p>The full Caddy line-up is pretty versatile. There are two body sizes: "normal" and Maxi. These can both be configured as a Panel Van, a Window Panel Van, Kombi (spartan passenger version), Caddy Life (family version), and a Camping version called the Caddy Tramper or Caddy Life Camper. A Caddy Life or Kombi seats up to five in two rows while a Caddy Life Maxi or Kombi Maxi seats up to seven in three rows. Caddy Life has a flexible seating system. The two rear bench seat rows can be taken out of the vehicle altogether to give the vehicle 2850 litres of cargo room; in addition, the Caddy Life has a 1500 kg towing capacity.</p>',
            ],
            [
                'title' => 'Mercedes Vito',
                'image' => 'vito.jpg',
                'description' => '<p>The Mercedes-Benz Vito is a light van produced by Mercedes-Benz. It is available as a standard panel van for cargo (called Vito), or with passenger accommodations substituted for part or all of the load area (called V-Class or Viano).</p>',
            ],
            [
                'title' => 'Mercedes V-Class/Viano',
                'image' => 'viano.jpg',
                'description' => '<p>The V-Class/Viano is a multi-purpose vehicle. The first generation went on sale in 1996. The second generation was introduced in 2004, and the vehicle received the new Viano name. In 2010, the vehicle was face-lifted with revised front and rear bumpers and lights. The interior was also improved with upgraded materials and new technology. The third generation was launched in 2014 and returned to being called V-Class.</p><p>The Viano is available in both rear- and four-wheel-drive configurations and comes in three lengths, two wheelbases and a choice of four petrol and diesel engines (as well as two specialist tuned models) coupled to either a six-speed manual or five-speed TouchShift automatic transmission.</p>',
            ],
        ];
        return ['types' => $types];
    }

}
