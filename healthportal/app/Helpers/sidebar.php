<?php

namespace App\Helpers;

class Sidebar{
	public static function get(){
		$pages = [
				[
					"header" => "SUITES", 
					"pages" => [
						[
							"label" => "Governance",
							"prefix" => "governance",
							"url" => "#",
							"pages" => [
								[ 
									"label" => "Finance",
									"url" => "finance",
									"restrictions" => []
								],
								[ 
									"label" => "Regulatory",
									"url" => "regulatory",
									"restrictions" => []
								]
							],
							"icon" => "home",
							"restrictions" => [] 
						],

						[
							"label" => "Health Services",
							"prefix" => "health-services",
							"url" => "/health-services",
							"pages" => [
								[ 
									"label" => "Private Practice",
									"url" => "private-practice",
									"restrictions" => []
								],
								[ 
									"label" => "Hospital Management",
									"url" => "hospital-management",
									"restrictions" => []
								]
							],
							"icon" => "services",
							"restrictions" => [] 
						],

						[
							"label" => "Patient Records",
							"prefix" => "client",
							"url" => "#",
							"icon" => "client",
							"restrictions" => [],
							"pages" => [
								[ 
									"label" => "Individual Records",
									"url" => "records",
									"restrictions" => []
								],
								[ 
									"label" => "Database View",
									"url" => "database",
									"restrictions" => []
								]
							]
						],

						[
							"label" => "Case Management",
							"prefix" => "case-management",
							"url" => "#",
							"pages" => [
								[ 
									"label" => "Sexually Transmitted Infections",
									"url" => "sti",
									"restrictions" => []
								],
								[ 
									"label" => "Tuberculosis",
									"url" => "tb",
									"restrictions" => []
								]
							],
							"icon" => "services",
							"restrictions" => [] 
						],

						[
							"label" => "Inventory Management",
							"prefix" => "inventory",
							"url" => "#",
							"icon" => "inventory",
							"restrictions" => [],
							"pages" => [
								[ 
									"label" => "Data Entry",
									"url" => "encode",
									"restrictions" => []
								],
								[ 
									"label" => "Reports",
									"url" => "reports",
									"restrictions" => []
								],
								[ 
									"label" => "Database View",
									"url" => "database",
									"restrictions" => []
								]
							]
						],

						[
							"label" => "Visualization",
							"prefix" => "visualization",
							"url" => "/visualization",
							"icon" => "bar",
							"restrictions" => [],
							"pages" => []
						],

						[
							"label" => "Analytics",
							"prefix" => "analytics",
							"url" => "/analytics",
							"icon" => "bar",
							"restrictions" => [],
							"pages" => []
						]
					]
				],

				[
					"header" => "SETTINGS", 
					"pages" => [
						[
							"label" => "Configuration",
							"prefix" => "config",
							"url" => "#",
							"icon" => "gear",
							"restrictions" => [],
							"pages" => [
								[ 
									"label" => "Data Dictionary",
									"url" => "data-dictionary",
									"restrictions" => []
								],
								[ 
									"label" => "Report Builder",
									"url" => "reports",
									"restrictions" => []
								],
								[ 
									"label" => "Query Builder",
									"url" => "query",
									"restrictions" => []
								],
								[ 
									"label" => "Class Builder",
									"url" => "class",
									"restrictions" => []
								],
								[ 
									"label" => "Network Setup",
									"url" => "network",
									"restrictions" => []
								],
								[ 
									"label" => "User Management",
									"url" => "user",
									"restrictions" => []
								]
							]
						],

						[
							"label" => "Account Management",
							"prefix" => "account/profile",
							"url" => "/account/profile",
							"icon" => "gear",
							"restrictions" => [],
							"pages" => []
						],

						[
							"label" => "Access Management",
							"prefix" => "account/user",
							"url" => "/account/users",
							"icon" => "reached",
							"restrictions" => [],
							"pages" => []
						],

						[
							"label" => "Clinic Information",
							"prefix" => "account/clinic",
							"url" => "/account/clinic",
							"icon" => "gear",
							"restrictions" => [config("constants.SHC_ADMIN")],
							"pages" => []
						],
					]
				]
			];

		return $pages;
	}

	public static function shcreps_menu(){
		$menu = [
			"clients" => [
				"label" => "Client Management",
				"prefix" => "client",
				"submenu" => [
					[ 
						"label" => "Records",
						"url" => "records",
						"restrictions" => []
					],
					[ 
						"label" => "Reports",
						"url" => "reports",
						"restrictions" => []
					],
					[ 
						"label" => "Database View",
						"url" => "database",
						"restrictions" => []
					],
					[ 
						"label" => "Duplicates Handling",
						"url" => "duplicates",
						"restrictions" => [config("constants.CENTRAL_ADMIN")]
					]
				]
			],

			"services" => [
				"label" => "Services",
				"prefix" => "services",
				"submenu" => [
					[ 
						"label" => "Data Entry",
						"url" => "encode",
						"restrictions" => [config("constants.SHC_ENCODER"), config("constants.SHC_ADMIN")]
					],
					[ 
						"label" => "Reports",
						"url" => "reports",
						"restrictions" => []
					],
					[ 
						"label" => "Database View",
						"url" => "database",
						"restrictions" => []
					]
				]
			],

			"reports" => [
				"label" => "Reports & Analytics",
				"prefix" => "",
				"submenu" => [
					[ 
						"label" => "STI Positivity Rates",
						"url" => "sti_graphs",
						"restrictions" => []
					],
					[
						"label" => "HIV",
						"prefix" => "hiv",
						"url" => "#",
						"restrictions" => [],
						"pages" => [
							[ 
								"label" => "Import Data",
								"url" => "import",
								"restrictions" => [config("constants.SHC_ENCODER"), config("constants.SHC_ADMIN")]
							],
							[ 
								"label" => "Reports",
								"url" => "reports",
								"restrictions" => []
							]
						]
					],

					[
						"label" => "CHITS",
						"prefix" => "chits",
						"url" => "#",
						"restrictions" => [],
						"pages" => [
							[ 
								"label" => "Import Data",
								"url" => "import",
								"restrictions" => [config("constants.SHC_ENCODER"), config("constants.SHC_ADMIN")]
							],
							[ 
								"label" => "Reports",
								"url" => "reports",
								"restrictions" => []
							]
						]
					],

					[
						"label" => "Reached",
						"prefix" => "reached",
						"url" => "#",
						"restrictions" => [],
						"pages" => [
							[ 
								"label" => "Import Data",
								"url" => "import",
								"restrictions" => [config("constants.SHC_ENCODER"), config("constants.SHC_ADMIN")]
							],
							[ 
								"label" => "Reports",
								"url" => "reports",
								"restrictions" => []
							]
						]
					]
				]
			],
		];

		return $menu;
	}

}