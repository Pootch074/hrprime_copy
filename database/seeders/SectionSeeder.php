<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;

class SectionSeeder extends Seeder
{
  public function run(): void
  {
    $sections = [
      [1, 'Regional Sub-Committee for the Welfare of Children', 'RSCWC'],
      [1, 'Legal Unit', 'LU'],
      [1, 'Internal Audit Unit', 'Internal Audit'],
      [1, 'Social Marketing Unit', 'SMU'],
      [15, 'Social Technology Unit', 'SOCTECH'],
      [1, 'Regional Juvenile Justice and Welfare Council', 'RJWC'],
      [1, 'Office of the Assistant Regional Director for Operations', 'OARDO'],
      [1, 'Office of the Assistant Regional Director for Administration', 'OARDA'],
      [4, 'Sustainable Livelihood Program Management Office', 'SLP'],
      [4, 'KALAHI CIDSS Program Management Office', 'KALAHI-CIDSS'],
      [5, 'Disaster Response and Rehabilitation Section', 'DRRS'],
      [5, 'Regional Resource Operations Section', 'RROS'],
      [5, 'Disaster Response Information Management Section', 'DRIMS'],
      [6, 'Crisis Intervention Section', 'CIS'],
      [6, 'Capability Building Section', 'CapBuild'],
      [6, 'Technical Assistance and Resource Augmentation', 'TARU'],
      [6, 'Community-Based Services Section', 'CBSS'],
      [6, 'Social Pension Program Management Office', 'SocPen'],
      [6, 'Supplimentary Feeding Program Management Office', 'SFP'],
      [6, 'Regional Alternative Child Care Office', 'RACCO'],
      [6, 'Center Based Services', 'CBS'],
      [6, 'Home for the Aged', 'HA'],
      [6, 'Regional Rehabilitation Center for Youth', 'RRCY'],
      [6, 'Center for Children with Special Needs', 'CCSN'],
      [6, 'Reception and Study Center for Children', 'RSCC'],
      [6, 'Home for Girls and Women', 'HGW'],
      [8, 'Pantawid Pamilyang Pilipino Program Management Division', '4Ps'],
      [9, 'Policy Development and Planning Section', 'PDPS'],
      [1, 'Anti-Red Tape Unit', 'ARTU'],
      [9, 'Standards Section', 'Standards'],
      [9, 'Information and Communications Technology Management Section', 'ICTMS'],
      [9, 'National Household Targeting System Program Management Office', 'NHTS'],
      [9, 'Unconditional Cash Transfer Program Management Office', 'UCT'],
      [10, 'Property Supply and Asset Management Section', 'PSAM'],
      [10, 'Procurement Section', 'Procurement'],
      [10, 'Records and Archives Management Section', 'RAMS'],
      [10, 'General Services Section', 'GSS'],
      [11, 'Human Resource Planning and Performance Management Section', 'HRPPM'],
      [11, 'Personnel Administration Section', 'HR-PAS'],
      [11, 'Learning Development Section', 'HR-LDS'],
      [11, 'Welfare Section', 'HR-Welfare'],
      [12, 'Accounting Section', 'Accounting'],
      [12, 'Budget Section', 'Budget'],
      [12, 'Cash Section', 'Cash'],
      [1, 'Office of the Regional Director', 'ORD'],
      [7, 'Provincial Social Welfare and Development Office (PSWADO)', 'PSWDO'],
      [4, 'EPHAP', 'EPHAP'],
      [1, 'Secretary of the Director', 'Secretary of the Director'],
      [13, 'HRMDD', 'HRMDD'],
      [13, 'Policy and Plans Division', 'PPD'],
      [13, 'Protective Services Division (PSD)', 'PSD'],
      [13, 'Administrative Division', 'Admin'],
      [13, 'Finance Division', 'Finance'],
      [13, 'Promotive Division', 'Promotive'],
      [13, 'DRMD', 'DRMD'],
      [10, 'Bids and Awards Committee Secretariat (BAC Sec)', 'BAC'],
      [9, 'RJJWC', 'RJJWC'],
      [6, 'RRCY', 'RRCY'],
      [6, 'RSCC', 'RSCC'],
      [6, 'HA', 'HA'],
      [6, 'HGW', 'HGW'],
      [6, 'CCSN', 'CCSN'],
      [8, 'Pantawid Pamilyang Pilipino Progam - City/Municipal Operations Office', '4Ps MOO'],
      [8, 'Pantawid Pamilyang Pilipino Program - Provincial Operations Office', '4Ps POO'],
      [8, 'Pantawid Pamilyang Pilipino Progam - Regional Management Office', '4Ps RMO'],
      [8, 'Provincial Social Welfare and Development Office (PSWADO)', '4Ps PSWADO'],
      [13, 'Office of The Division Chief', 'ODC'],
      [1, 'ARDO/ARDA', 'For RD'],
      [10, 'Property and Supply Section', 'PSS'],
      [6, 'Angel', 'ANGEL'],
      [9, 'Targeted Cash Transfer Program Management Office', 'TCTPMO'],
      [6, 'Angels haven', 'AH'],
      [9, 'Technical Advisory Assistance and Other Related Support Services', 'TAORRS'],
      [9, 'Beneficiary First', 'BF'],
      [8, 'Pantawid Pamilyang Pilipino Progam - DAVAO ORIENTAL', '4Ps DO'],
      [8, 'Pantawid Pamilyang Pilipino Progam - DAVAO DE ORO', '4Ps DDO'],
      [8, 'Pantawid Pamilyang Pilipino Progam - DAVAO DEL SUR', '4Ps DDS'],
      [8, 'Pantawid Pamilyang Pilipino Progam - DAVAO OCCIDENTAL', '4Ps DavOcc'],
      [8, 'Pantawid Pamilyang Pilipino Progam - DAVAO DEL NORTE', '4Ps DDN'],
      [8, 'Pantawid Pamilyang Pilipino Progam - DAVAO CITY', '4Ps DC'],
      [15, 'PAG-ABOT', 'PAGABOT'],
    ];

    foreach ($sections as [$divisionId, $name, $abbr]) {
      Section::updateOrCreate(
        [
          'division_id' => $divisionId,
          'name' => $name,
        ],
        [
          'abbreviation' => $abbr,
        ]
      );
    }
  }
}
