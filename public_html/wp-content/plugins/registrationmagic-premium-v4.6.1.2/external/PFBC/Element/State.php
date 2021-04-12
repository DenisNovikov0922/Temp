<?php
class Element_State extends Element_Select {
	public function __construct($label, $name, array $properties = null) {
		$options = array(
                  "" =>__("--Select State--",'registrationmagic-gold'),
                  "AL" =>__("Alabama",'registrationmagic-gold'),
                  "AK" =>__("Alaska",'registrationmagic-gold'),
                  "AZ" =>__("Arizona",'registrationmagic-gold'),
                  "AR" =>__("Arkansas",'registrationmagic-gold'),
                  "CA" =>__("California",'registrationmagic-gold'),
                  "CO" =>__("Colorado",'registrationmagic-gold'),
                  "CT" =>__("Connecticut",'registrationmagic-gold'),
                  "DE" =>__("Delaware",'registrationmagic-gold'),
                  "DC" =>__("District of Columbia",'registrationmagic-gold'),
                  "FL" =>__("Florida",'registrationmagic-gold'),
                  "GA" =>__("Georgia",'registrationmagic-gold'),
                  "HI" =>__("Hawaii",'registrationmagic-gold'),
                  "ID" =>__("Idaho",'registrationmagic-gold'),
                  "IL" =>__("Illinois",'registrationmagic-gold'),
                  "IN" =>__("Indiana",'registrationmagic-gold'),
                  "IA" =>__("Iowa",'registrationmagic-gold'),
                  "KS" =>__("Kansas",'registrationmagic-gold'),
                  "KY" =>__("Kentucky",'registrationmagic-gold'),
                  "LA" =>__("Louisiana",'registrationmagic-gold'),
                  "ME" =>__("Maine",'registrationmagic-gold'),
                  "MD" =>__("Maryland",'registrationmagic-gold'),
                  "MA" =>__("Massachusetts",'registrationmagic-gold'),
                  "MI" =>__("Michigan",'registrationmagic-gold'),
                  "MN" =>__("Minnesota",'registrationmagic-gold'),
                  "MS" =>__("Mississippi",'registrationmagic-gold'),
                  "MO" =>__("Missouri",'registrationmagic-gold'),
                  "MT" =>__("Montana",'registrationmagic-gold'),
                  "NE" =>__("Nebraska",'registrationmagic-gold'),
                  "NV" =>__("Nevada",'registrationmagic-gold'),
                  "NH" =>__("New Hampshire",'registrationmagic-gold'),
                  "NJ" =>__("New Jersey",'registrationmagic-gold'),
                  "NM" =>__("New Mexico",'registrationmagic-gold'),
                  "NY" =>__("New York",'registrationmagic-gold'),
                  "NC" =>__("North Carolina",'registrationmagic-gold'),
                  "ND" =>__("North Dakota",'registrationmagic-gold'),
                  "OH" =>__("Ohio",'registrationmagic-gold'),
                  "OK" =>__("Oklahoma",'registrationmagic-gold'),
                  "OR" =>__("Oregon",'registrationmagic-gold'),
                  "PA" =>__("Pennsylvania",'registrationmagic-gold'),
                  "RI" =>__("Rhode Island",'registrationmagic-gold'),
                  "SC" =>__("South Carolina",'registrationmagic-gold'),
                  "SD" =>__("South Dakota",'registrationmagic-gold'),
                  "TN" =>__("Tennessee",'registrationmagic-gold'),
                  "TX" =>__("Texas",'registrationmagic-gold'),
                  "UT" =>__("Utah",'registrationmagic-gold'),
                  "VT" =>__("Vermont",'registrationmagic-gold'),
                  "VA" =>__("Virginia",'registrationmagic-gold'),
                  "WA" =>__("Washington",'registrationmagic-gold'),
                  "WV" =>__("West Virginia",'registrationmagic-gold'),
                  "WI" =>__("Wisconsin",'registrationmagic-gold'),
                  "WY" =>__("Wyoming",'registrationmagic-gold')
            );
	    parent::__construct($label, $name, $options, $properties);
    }
}
