<?php

namespace App\Services;

class CategorySeoContentService
{
    /**
     * Get SEO-optimized intro paragraph for category
     */
    public function getIntroContent(string $categorySlug): string
    {
        $content = $this->getCategoryContent();
        return $content[$categorySlug]['intro'] ?? '';
    }

    /**
     * Get FAQ items for category
     */
    public function getFaqItems(string $categorySlug): array
    {
        $content = $this->getCategoryContent();
        return $content[$categorySlug]['faqs'] ?? [];
    }

    /**
     * Category-specific SEO content
     */
    private function getCategoryContent(): array
    {
        return [
            'banking' => [
                'intro' => 'Banking sector jobs in India offer lucrative career opportunities through IBPS, SBI, RBI, and other nationalized banks. The IBPS PO 2026 and IBPS Clerk 2026 examinations attract millions of candidates seeking positions in public sector banks. SBI PO 2026 notification is expected to announce vacancies for Probationary Officers across India. RBI Grade B 2026 recruitment offers prestigious positions in the Reserve Bank of India with excellent salary packages. Banking jobs require graduation with strong quantitative aptitude, reasoning ability, and English language skills. Candidates preparing for IBPS exams 2026 should focus on current affairs, banking awareness, and computer knowledge. The selection process typically includes prelims, mains, and interview stages for officer-level positions.',
                'faqs' => [
                    [
                        'question' => 'What is the eligibility for IBPS PO 2026?',
                        'answer' => 'Candidates must have a graduation degree from a recognized university. Age limit is 20-30 years with relaxation for reserved categories.',
                    ],
                    [
                        'question' => 'How to prepare for SBI Clerk 2026 exam?',
                        'answer' => 'Focus on quantitative aptitude, reasoning, English, general awareness, and computer knowledge. Practice previous year papers and take mock tests regularly.',
                    ],
                    [
                        'question' => 'What is the salary of IBPS PO 2026?',
                        'answer' => 'IBPS PO salary ranges from ₹36,000 to ₹70,000 per month including basic pay, DA, HRA, and other allowances.',
                    ],
                    [
                        'question' => 'When will RBI Grade B 2026 notification release?',
                        'answer' => 'RBI Grade B notification is typically released in May-June every year. Check JobOne.in for latest updates.',
                    ],
                    [
                        'question' => 'What is the exam pattern for banking exams 2026?',
                        'answer' => 'Most banking exams have prelims (objective) and mains (objective + descriptive) followed by an interview for final selection.',
                    ],
                ],
            ],

            'railways' => [
                'intro' => 'Indian Railways recruitment 2026 offers massive employment opportunities through RRB NTPC, RRB Group D, and Railway ALP examinations. The RRB NTPC 2026 notification is expected to announce over 35,000 vacancies for non-technical popular categories including commercial clerk, accounts clerk, and station master positions. RRB Group D 2026 recruitment provides entry-level opportunities for track maintainer, helper, and assistant positions across Indian Railways zones. Railway ALP 2026 (Assistant Loco Pilot) offers technical positions with excellent career growth and salary benefits. Candidates preparing for railway exams 2026 should focus on general awareness, mathematics, reasoning, and general science subjects. The selection process includes computer-based tests, physical efficiency test, and document verification stages.',
                'faqs' => [
                    [
                        'question' => 'What is the age limit for RRB NTPC 2026?',
                        'answer' => 'Age limit is 18-33 years for RRB NTPC with relaxation for reserved categories as per government norms.',
                    ],
                    [
                        'question' => 'How many posts in RRB Group D 2026?',
                        'answer' => 'RRB Group D typically announces 60,000-1,00,000 vacancies across all railway zones in India.',
                    ],
                    [
                        'question' => 'What is the qualification for Railway ALP 2026?',
                        'answer' => 'Candidates must have ITI or 10th + NCTVT/SCVT certificate in relevant trades for Railway ALP positions.',
                    ],
                    [
                        'question' => 'What is the salary in RRB NTPC 2026?',
                        'answer' => 'RRB NTPC salary ranges from ₹19,900 to ₹35,400 per month depending on the pay level and position.',
                    ],
                    [
                        'question' => 'How to download RRB admit card 2026?',
                        'answer' => 'Visit the official RRB regional website, enter registration number and date of birth to download admit card.',
                    ],
                ],
            ],

            'ssc' => [
                'intro' => 'Staff Selection Commission (SSC) conducts recruitment for various Group B and Group C posts in central government ministries and departments. SSC CGL 2026 (Combined Graduate Level) is one of the most prestigious exams offering positions like Income Tax Inspector, Assistant Audit Officer, and Statistical Investigator. SSC CHSL 2026 (Combined Higher Secondary Level) recruits for LDC, DEO, and Postal Assistant positions across India. SSC MTS 2026 (Multi-Tasking Staff) provides opportunities for 10th pass candidates in central government offices. SSC GD 2026 recruitment is conducted for constable positions in CAPF, NIA, SSF, and Rifleman in Assam Rifles. Candidates preparing for SSC exams 2026 should master quantitative aptitude, English language, general intelligence, and general awareness subjects for competitive success.',
                'faqs' => [
                    [
                        'question' => 'What is the eligibility for SSC CGL 2026?',
                        'answer' => 'Candidates must have a bachelor\'s degree from a recognized university. Age limit varies from 18-32 years depending on the post.',
                    ],
                    [
                        'question' => 'How many tiers in SSC CGL 2026 exam?',
                        'answer' => 'SSC CGL has 4 tiers: Tier-I (CBT), Tier-II (CBT), Tier-III (Descriptive), and Tier-IV (Skill Test/CPT).',
                    ],
                    [
                        'question' => 'What is the salary of SSC CHSL 2026?',
                        'answer' => 'SSC CHSL salary ranges from ₹19,900 to ₹63,200 per month including basic pay and allowances.',
                    ],
                    [
                        'question' => 'When will SSC MTS 2026 notification release?',
                        'answer' => 'SSC MTS notification is typically released in May-June every year. Check JobOne.in for updates.',
                    ],
                    [
                        'question' => 'What is the physical test for SSC GD 2026?',
                        'answer' => 'SSC GD physical test includes race (male: 5km in 24 min, female: 1.6km in 8.5 min) and physical measurement.',
                    ],
                ],
            ],

            'upsc' => [
                'intro' => 'Union Public Service Commission (UPSC) is India\'s premier recruiting agency for civil services and other prestigious government positions. UPSC CSE 2026 (Civil Services Examination) is the gateway to IAS, IPS, IFS, and other Group A services with nationwide authority and responsibility. UPSC NDA 2026 (National Defence Academy) recruits candidates for Indian Army, Navy, and Air Force through a rigorous selection process. UPSC CDS 2026 (Combined Defence Services) offers entry into Indian Military Academy, Naval Academy, Air Force Academy, and Officers Training Academy. UPSC conducts specialized recruitments for Indian Forest Service, Indian Economic Service, and various technical services. Candidates preparing for UPSC exams 2026 require comprehensive knowledge of current affairs, Indian polity, history, geography, economy, and analytical skills for essay and interview stages.',
                'faqs' => [
                    [
                        'question' => 'What is the age limit for UPSC CSE 2026?',
                        'answer' => 'Age limit is 21-32 years for general category with relaxation up to 37 years for OBC and 42 years for SC/ST.',
                    ],
                    [
                        'question' => 'How many attempts in UPSC CSE 2026?',
                        'answer' => 'General category: 6 attempts, OBC: 9 attempts, SC/ST: unlimited attempts until age limit.',
                    ],
                    [
                        'question' => 'What is the qualification for UPSC NDA 2026?',
                        'answer' => 'Candidates must have passed 12th or equivalent. Age limit is 16.5-19.5 years. Only unmarried male candidates are eligible.',
                    ],
                    [
                        'question' => 'What is the exam pattern for UPSC CDS 2026?',
                        'answer' => 'CDS exam has English, GK, and Elementary Mathematics papers followed by SSB interview for final selection.',
                    ],
                    [
                        'question' => 'What is the salary of IAS officer 2026?',
                        'answer' => 'IAS officer salary starts from ₹56,100 per month and can go up to ₹2,50,000 per month for Cabinet Secretary.',
                    ],
                ],
            ],

            'state-psc' => [
                'intro' => 'State Public Service Commissions conduct recruitment for state government administrative and executive positions across India. UPPSC 2026 (Uttar Pradesh PSC) conducts PCS examination for administrative services in UP with thousands of vacancies annually. BPSC 2026 (Bihar PSC) recruits for state civil services through combined competitive examination with prelims, mains, and interview stages. MPPSC 2026 (Madhya Pradesh PSC) offers opportunities in state administration, police, forest, and other services. RPSC 2026 (Rajasthan PSC) conducts RAS examination for administrative positions in Rajasthan government. State PSC exams require knowledge of state-specific current affairs, history, geography, economy, and administrative structure. Candidates preparing for state PSC 2026 should focus on both general studies and state-specific subjects for comprehensive preparation and competitive advantage.',
                'faqs' => [
                    [
                        'question' => 'What is the eligibility for UPPSC PCS 2026?',
                        'answer' => 'Candidates must have a bachelor\'s degree from a recognized university. Age limit is 21-40 years.',
                    ],
                    [
                        'question' => 'How many stages in BPSC 2026 exam?',
                        'answer' => 'BPSC exam has 3 stages: Prelims (objective), Mains (descriptive), and Interview (personality test).',
                    ],
                    [
                        'question' => 'What is the salary in MPPSC 2026?',
                        'answer' => 'MPPSC state service salary ranges from ₹44,900 to ₹1,42,400 per month depending on the position.',
                    ],
                    [
                        'question' => 'When will RPSC RAS 2026 notification release?',
                        'answer' => 'RPSC RAS notification is typically released in June-July every year. Check JobOne.in for updates.',
                    ],
                    [
                        'question' => 'What is the exam pattern for state PSC 2026?',
                        'answer' => 'Most state PSC exams follow prelims (objective), mains (descriptive), and interview pattern similar to UPSC.',
                    ],
                ],
            ],

            'police' => [
                'intro' => 'Police recruitment in India offers rewarding careers in law enforcement and public safety across state and central police forces. UP Police 2026 recruitment announces massive vacancies for constable, SI, and other positions in Uttar Pradesh Police with competitive salary and benefits. Delhi Police 2026 conducts recruitment for constable, head constable, and sub-inspector positions in the national capital with excellent career prospects. Bihar Police 2026 offers opportunities in state police force through written exam, physical test, and medical examination. State police recruitments require physical fitness, mental alertness, and knowledge of general studies, reasoning, and numerical ability. Central police forces like CRPF, BSF, CISF, and ITBP recruit through SSC GD examination for constable positions. Candidates preparing for police exams 2026 should focus on physical fitness training alongside academic preparation for comprehensive success.',
                'faqs' => [
                    [
                        'question' => 'What is the height requirement for UP Police 2026?',
                        'answer' => 'Male: 168 cm (160 cm for reserved), Female: 152 cm (147 cm for reserved) for UP Police constable.',
                    ],
                    [
                        'question' => 'What is the physical test for Delhi Police 2026?',
                        'answer' => 'Male: 5 km race in 25 minutes, Female: 1.6 km race in 8.5 minutes for Delhi Police constable.',
                    ],
                    [
                        'question' => 'What is the salary of Bihar Police SI 2026?',
                        'answer' => 'Bihar Police SI salary ranges from ₹35,400 to ₹1,12,400 per month including basic pay and allowances.',
                    ],
                    [
                        'question' => 'What is the age limit for police constable 2026?',
                        'answer' => 'Age limit is typically 18-25 years for police constable with relaxation for reserved categories.',
                    ],
                    [
                        'question' => 'How to prepare for police exam 2026?',
                        'answer' => 'Focus on general knowledge, reasoning, numerical ability, and maintain physical fitness through regular exercise.',
                    ],
                ],
            ],

            'teaching' => [
                'intro' => 'Teaching jobs in India provide stable careers in education sector through CTET, state TETs, and direct recruitment by educational institutions. CTET 2026 (Central Teacher Eligibility Test) is mandatory for teaching positions in central government schools like KVS, NVS, and Tibetan schools. KVS recruitment 2026 (Kendriya Vidyalaya Sangathan) announces vacancies for PGT, TGT, and PRT teachers across 1200+ Kendriya Vidyalayas in India. DSSSB 2026 (Delhi Subordinate Services Selection Board) conducts recruitment for teaching positions in Delhi government schools with competitive salary structure. State TET examinations are conducted by respective state governments for eligibility to teach in state government schools. Teaching positions require B.Ed or D.El.Ed qualification along with subject expertise and communication skills. Candidates preparing for teaching exams 2026 should focus on child development, pedagogy, subject knowledge, and teaching methodology for successful career in education sector.',
                'faqs' => [
                    [
                        'question' => 'What is the eligibility for CTET 2026?',
                        'answer' => 'For Primary: 12th + D.El.Ed/B.El.Ed, For Elementary: Graduation + B.Ed with 50% marks.',
                    ],
                    [
                        'question' => 'What is the salary of KVS PGT 2026?',
                        'answer' => 'KVS PGT salary ranges from ₹44,900 to ₹1,42,400 per month as per 7th Pay Commission.',
                    ],
                    [
                        'question' => 'How many papers in CTET 2026 exam?',
                        'answer' => 'CTET has 2 papers: Paper-I for classes I-V and Paper-II for classes VI-VIII. Both are objective type.',
                    ],
                    [
                        'question' => 'What is the validity of CTET certificate 2026?',
                        'answer' => 'CTET certificate is valid for lifetime for all teaching positions in central government schools.',
                    ],
                    [
                        'question' => 'When will DSSSB TGT 2026 notification release?',
                        'answer' => 'DSSSB releases multiple notifications throughout the year. Check JobOne.in for latest updates.',
                    ],
                ],
            ],

            'defence' => [
                'intro' => 'Defence jobs in India offer prestigious careers in Indian Army, Navy, and Air Force through various entry schemes and examinations. Indian Army recruitment 2026 conducts Agniveer, TES, TGC, and other schemes for officer and soldier positions with excellent training and benefits. Indian Navy 2026 offers opportunities through SSR, AA, MR schemes for sailors and officer entry through INET and University Entry Scheme. Indian Air Force 2026 recruits through Agniveer Vayu, Group X, Group Y for airmen and AFCAT for officer positions. Defence forces require physical fitness, mental toughness, and dedication to serve the nation with honor and pride. NDA and CDS examinations conducted by UPSC are premier entry routes for officer positions in all three defence forces. Candidates preparing for defence exams 2026 should maintain excellent physical fitness, academic knowledge, and leadership qualities for successful military career.',
                'faqs' => [
                    [
                        'question' => 'What is the age limit for Indian Army Agniveer 2026?',
                        'answer' => 'Age limit is 17.5-21 years for Agniveer recruitment in Indian Army.',
                    ],
                    [
                        'question' => 'What is the physical test for Indian Navy 2026?',
                        'answer' => 'Navy physical test includes 1.6 km run in 7 minutes, 20 squats, 10 push-ups for sailors entry.',
                    ],
                    [
                        'question' => 'What is the qualification for Air Force Group X 2026?',
                        'answer' => 'Candidates must have passed 12th with Physics, Maths, and English with 50% marks for Group X.',
                    ],
                    [
                        'question' => 'What is the salary of Agniveer 2026?',
                        'answer' => 'Agniveer salary starts from ₹30,000 per month with annual increment and Seva Nidhi package after 4 years.',
                    ],
                    [
                        'question' => 'How to join Indian Army as officer 2026?',
                        'answer' => 'Join through NDA, CDS, TES, TGC, or AFMC examination followed by SSB interview and medical test.',
                    ],
                ],
            ],

            'insurance' => [
                'intro' => 'Insurance sector jobs in India offer stable careers in public sector insurance companies like LIC, GIC, and specialized insurance corporations. LIC AAO 2026 (Life Insurance Corporation Assistant Administrative Officer) recruitment offers managerial positions with excellent salary and career growth opportunities. LIC ADO 2026 (Apprentice Development Officer) provides entry-level positions in sales and marketing with attractive incentives and commission structure. NIACL 2026 (New India Assurance Company Limited) conducts recruitment for Assistant and Administrative Officer positions in general insurance sector. Insurance jobs require graduation with strong communication skills, sales aptitude, and understanding of insurance products and regulations. The selection process typically includes online examination, descriptive test, and interview for final selection. Candidates preparing for insurance exams 2026 should focus on reasoning, quantitative aptitude, English language, general awareness, and insurance awareness for competitive advantage in examinations.',
                'faqs' => [
                    [
                        'question' => 'What is the eligibility for LIC AAO 2026?',
                        'answer' => 'Candidates must have a bachelor\'s degree with 60% marks. Age limit is 21-30 years.',
                    ],
                    [
                        'question' => 'What is the salary of LIC ADO 2026?',
                        'answer' => 'LIC ADO salary ranges from ₹21,865 to ₹47,920 per month plus attractive incentives and commission.',
                    ],
                    [
                        'question' => 'What is the exam pattern for NIACL 2026?',
                        'answer' => 'NIACL exam has prelims (objective), mains (objective + descriptive), and interview for final selection.',
                    ],
                    [
                        'question' => 'When will LIC AAO 2026 notification release?',
                        'answer' => 'LIC AAO notification is typically released in February-March every year. Check JobOne.in for updates.',
                    ],
                    [
                        'question' => 'What is the job profile of insurance officer 2026?',
                        'answer' => 'Insurance officers handle policy sales, customer service, claim processing, and branch operations management.',
                    ],
                ],
            ],

            'judiciary' => [
                'intro' => 'Judicial services in India offer prestigious careers in the legal system through state judicial service examinations and direct recruitment. State Judicial Service examinations are conducted by respective High Courts for appointment as Civil Judge (Junior Division) or Judicial Magistrate First Class. The selection process includes preliminary examination (objective), mains examination (descriptive), and interview followed by training at State Judicial Academy. Candidates must have LLB degree from recognized university with minimum 50-55% marks and be enrolled as advocate with Bar Council. Judicial officers enjoy high social status, job security, and excellent salary structure as per state government pay scales. The examination syllabus covers constitutional law, criminal law, civil law, evidence act, and procedural laws comprehensively. Candidates preparing for judicial service 2026 should have thorough knowledge of bare acts, landmark judgments, and legal reasoning skills for successful career in judiciary.',
                'faqs' => [
                    [
                        'question' => 'What is the eligibility for judicial service 2026?',
                        'answer' => 'Candidates must have LLB degree with 50-55% marks and be enrolled as advocate. Age limit is 21-35 years.',
                    ],
                    [
                        'question' => 'What is the salary of civil judge 2026?',
                        'answer' => 'Civil Judge salary ranges from ₹27,700 to ₹44,870 per month as per state government pay scale.',
                    ],
                    [
                        'question' => 'How many stages in judicial service exam 2026?',
                        'answer' => 'Judicial service exam has 3 stages: Prelims (objective), Mains (descriptive), and Interview.',
                    ],
                    [
                        'question' => 'What subjects are in judicial service mains 2026?',
                        'answer' => 'Mains includes constitutional law, criminal law, civil law, evidence act, and procedural laws.',
                    ],
                    [
                        'question' => 'When will state judicial service 2026 notification release?',
                        'answer' => 'Different states release notifications at different times. Check JobOne.in for state-wise updates.',
                    ],
                ],
            ],
        ];
    }
}
