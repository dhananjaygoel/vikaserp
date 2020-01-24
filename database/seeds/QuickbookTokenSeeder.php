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
                        'client' => 'ABR8lByNwjjHYaPyBaLSqvR5Maf6iIeWNermMGnngpV7QJccyo',
                        'secret' => 'pe7YWyv0ITXtTEX8hzhawSuZ4zGkIXLPHa4b6jFb',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..cQDWaOIKsFKen-pXmUiVzg.cygyISsgbl3RIal7ONmGV2dgwBzuklxTjpMDMmMQOr0j0gGmbKhgIra0ibNB09RABErm2ytZjtovCHfcgNjeWjV-RQdKhavWxnAKpfCzrdvzoF-XCFfRkAH-u1aQH-yU26P9g1P1q0pzZPdgM91u6FdOKqFmlRvUMs5oco98k6dDZ_t2LETy1eoz-eQW9OUClVqsT39o0pv7hjHpe6oYOd_I5j6_KdzIvui8FHYrpNx9-BdN8D-5JfjVMoSzOjTO3HqmuuwYUHBqTHK_-N90ykD6UkTB-RJ5dUlZhofh95rTy7419_cCMvkzkD_vMYlYn171wIfX7D-m0ZvLIOKcuLtqVOv7OQbroSHeNmUEQV0XjcviyjilXCi4mqWcgh3G01fD2dSC4mjXOzFzmwj4JcD1n3AZthGrRWeBCXjxkmaEBjOP-MxUtUOsuQeBXU4wD1Xgoepd_RboqXKL7oLzjBSf2azEQpz3e_JcZaN4ONI5Ci5IpQ4pGJqvKnXdenl3QBjjqHrKVAHiiGbTep33BR43IKDeAFMjMksqLoep5pIm9QUvs4jOSwo9P4UyrutOQeeG2lCynfYhcDhKhM2TchgPeaFtF03ItOCYuKqtaCzDUgknAKsVefyDDSQQBcILQKV6B1cd7oeDl-JAPAPcR4bWSa60mShMoBtlDIxocUqTPYl4b4piQ5U4ffd5M7SC2Ij27sipv_igcCRvBffXipCgYXIcOrmuuyaIADxAqkI.5xFSFk5AazvCRlVtS0gOVg',
                        'refresh_token' => 'AB11588496854vgTAGQ0jSvLyhXa0CWofOUHKNJulEu92TkSAB'
                    ),
                    //Plus GST account
                    array(
                        'id' => '2',
                        'client' => 'AB9iBJ9tUBWTeCn3WNIgVLwQ7TAbrXaC09pNOZ6BzlSxMnyjdt',
                        'secret' => 'FpGX1zIl7jcWLEQUmXs0J09KnQw5MtSvmKCq5kVO',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..Tn-uYKoIxLu9kGRIGKCg3Q.DlMfty3Oloi-NK4wS1yU3OtfqO-4aqPwjVr5kHWUpaolkOdoQ2rr32IEpo9sxXOAiDt1mqLyI1Mz9dpc6yN6ZuHstiDVnp_9uiNrhZCZwwUMKSd7a71sOx5HeDEAfqOlEVdY2g5HJfWYsdcy3OaoD7jayiFq7mLXvUzR4TvYXF8glQzymvYLjd2pLvTo6tfeJKHrlasAA9aQXHg4OATQnlXd9ok_gbDzK9i-VdQ137UDXfB7cfmeMbBty7cQka9rtneqwyfRNJmFqrLWq7wBQr4-9PvE6isgW6W57wvpmYpMe-n_VsoDfgZQQZgPs7_T-xGLtqtfkOuKrNQs6SMQSUQ3GFxhLW6HsRA6u3AZmc07El8_f3kF3cESKA-SXA5yhvYfYdFt0taqBGxmbebXSQEl4tjRZ_YzFEyiruvIGhmR7TsIwJ4YY8vyGZhHo1oC27Sutz0IHsjZXONxIxQl5ekmQ4YMvo05eGeo9EDSGRcRmg-WJncd9RVTWgdzaiuJhtI8aPMvusGIH5q7Xe3W8uiB6lHGbuQ61WFj_BRnmtS7mqs7F7Yy1s8Yh-vCFO3bZoCvaieQbyEbZVZiiKO_2UcJCX-eX3RNJ4OrUO065M_7XL1oF9N-bYH2bLP2g842nowuoQZdEcZpjWLIcT7Rck4lZViJT9HNQ8CL8l9r6d1RJAxHS77TDSuBhDpQVmvOvMabNuzSksPE_eX0LYgoUpRbVEaEqAEPCOMXOjFu7fE.3G4r1N1aEUZ_-m1rFjX39A',
                        'refresh_token' => 'AB11588496709y6GIx6tc4bvmM91naA3DH8goSIu1AkFvgikQH'
                    )
        ));
    }
}
