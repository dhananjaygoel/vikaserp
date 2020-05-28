<?php

use Illuminate\Database\Seeder;

class QuickbookTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    public function run()
    {
        DB::table('quickbook_token')->truncate();
        DB::table('quickbook_token')->insert(
                array(
                    //All inclusive account
                    array(
                        'id' => '1',
                        'client' => 'ABfN2ld2b7J2FTN22uTqLPCFkx9f99Sj4YvDsMmVHD3mNUyuWU',
                        'secret' => '9peqxCWOzdpp1cEKPnAsPAAyEz77Z930q2QADnoe',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..6I6IIl8pO9unv-IXhrMCIg.uNcD-fJcqXJN1zboF2eiXoEzvs-JNPyjjUtHibbY7UXm3m-RmupkN25YgkG_NGa6CMc8bm8LViaYR4ezmlfW3F8megAWgpC7iyQilv3mg6ggmEyJaR2mC4gFaNg8rearBQjZ23RRdgzDlWsqZbsj0RP6pFyuyznYroXeQJ0N7jSWtDoisTnUh01oPLYURjGyQmVRB8EMRxKUoLDuybsVQEhrtV9qWAL8CxFVihBeyK2cw6_kLUtYounOVxh1U1fATUdFDmdoQjDoICTNBYvsMyxk_FVAoQ6wKpjwQP5lSgl1xT2I30sPHB0853PAgTWsVN2Bo05u1vZF_m7N_Kb27cyHM4r6GOA_OOApLgIPGr23fdDaspbOoYOj_Owmq-hPmG4NDq1TAXx8oCGR7Hu1VXX5NuHLyeLmQpGkF6ova2u_JKKA4u4zfrd1f52z7fy7INUHmsxw-TeDvlyVeMxi4Z3B8BlIMFce99zQN2ztcx4FEXlhQfG1DdbCdHir4_LQ1_VnpcZ4_M2HZTs7tQtQgHuddcDt-bEZ0HIJaNTUlIkxD90IW--97tOqWekLIA0afigXy3TiY7rcw1nqZPDAtTs_Mi7p6FMU-pXJDEW75sFSJlERK9AzA6zmnNWXue7o5H_mS7n5-KtdrPzF1GwtodvRArlcNn5F9O-b1iA2VgFkK2HxKLqL_Aly9mAcTxH5KuvzEaIsW8oCLvCZjDj10lwtX40kMVJmon8gdxeCXa4.H4Kl4cmvn4X48u_qwcHCAg',
                        'refresh_token' => 'AB11599291540tvShBuHtKIdxduHJWYa78hAKwfEgeo5F8rkad'
                    ),
                    //Plus GST account
                    array(
                        'id' => '2',
                        'client' => 'ABSSab3mSZlIGJLQ1tCZSKKpd7LN7OI3zacIfFW0hLsHLVMnyp',
                        'secret' => 'PQLjc67R23HENkuvNmgj2YY3QVn1OcBHXNtIJvpP',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..Q3OFYZGnrzEtRJQKJxcu2Q.F12FPN0En9lKeGl0sHsdzPEcztUfDKvFguEPFRLJJzjc0DhEUtil41WhX1U21T7zEVriJ3n-YwdtPGGuHdTEV7uaY2iho9o7P3sqRwemkYG6i_exc3WKVGDJYpFKZXoG2yYjGXM10OujRBASurRiGqYJBjs7XYYSPQ4yhFR2jni7u8VWfYJTGc6bIBjkjUXjNEREl3Kg0dWhUHZvVUf8jS3T-JK4IbmyuV9KB2APcxldHsT6Q3G4SuleEq5vuV60WgauVbQV_ELGho_MlsNqJjMenKEeDLdj8JFQqQU-ttu3NF2m9RidVyAKq7jEigWE7CEqFodpMoTAs4C7tPkMRv_dof6ueyWMhrDKWVJB9c_z8GifbEVRAybswG-Zst04mSs4mpsYuwemvyrFEmrDse1RWcdGvGP2pkXvJ6TGR-FwoKkllOJzN4qlbK_BYWWzwzRity8le4VUpEPqyuWktkx2lxBZm7r0BYxwhBcOkvd98GGjFC6G7xMZo8KQT968adeUqRKRJVr6NyFff0oSqsZsBk2OAuwZCOcoHHJsowMIAF0lSyC3jy9k-Kx9syDnmQZfNRKlFQdVgnjd3_zY1Rvj2raxcgsPsBvUbG3CB4u5luOE_KgT7_dVEm95w47cXcXn3xtimsSv_M4Z-fT23mG3VT4gX0bPHv0F667zFKA_S9aC9sfHVDf8udd5xgGlFYndtyXmgWu-GBz-94Bh_W70rEbpW4tH0VBwdbCL0Oo.e2Jqit3ZzVm_A_2ORZBpoQ',
                        'refresh_token' => 'AB11599291128weNECsLZnr5nOSmHHHIRIraXz3AsyiwRh0yep'
                    )
        ));
    }
}
