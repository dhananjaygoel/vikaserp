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
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..9CJf2E1u7ZF8Y_13k_Mqfw.lwW5-lFN_PyMzwXzqP9JSOQxeZn-aPRdIE-0S4c8W2XYdPKR9mJ01OWRiA-SbdG-AjzbbLV5eT_jaSN2IvIRue3_eXmXJjiTZR5OY316za5u_i8z91oVzVpjYMre1ixg1AYko2Liysb-G7A2_bIEM1jD5juOO-EPyo6UlyLYR8vHTU3H_mlL-7_0ELkLeIh67xvAQcVDCGtyKufHVe3xuhqh6jrFosOEzSEhFLXgs_47GgBObz_ct62rxOf5LmrURo7UTWhtzCe7Ph9oV_EKvOTGD-Gq2rHFsPt1GZu9k_r-nfM1xTea_qNCXhMKBr5GB3Pt3PP6Nk_knGhgs9KoYXR6QScJ1HHi8SWQhIYPlUGKvDa0pNloOXcCBrxl5QFbHJU5IIlezluUbQ92Gmv58_YywwjxQA7CMSe5bx5uBPcQ4mkMnpjIwCUOYwcLG3DdhFbh-t7-UVGRfUHpfJGZleYRGZp7V7FX0Hz6OKvUIr-3yvk4Gzcs0yu4BOaDXySwr8Z1-JE9R8oxTRzVFuepQl6DttEzUyfPAq3w5Lo7UJknWXJ8RTKuk5B0xzHtjOfnm8p8v089O7xmSMTYBdrLOeGNUzIvFwoe8xgkEZAUd39A72jijvPoKutF3GQgqsS8dF2ElIDlAxsHwY8yrnGd-raIzqAQPCptWhvESh2UUsPWLIR3AVI2AYoyBFvIWqiL0iwiP3xmk3Dobz40FLc0W6t8__UarS0VmzTbqXb292s.vrm3KQOZ6Ozh4v68xyxwXw',
                        'refresh_token' => 'AB116011868158pry3lvqNfmg4rv9rOXtcuLMyoJB4NkulVtJN'
                    ),
                    //Plus GST account
                    array(
                        'id' => '2',
                        'client' => 'ABSSab3mSZlIGJLQ1tCZSKKpd7LN7OI3zacIfFW0hLsHLVMnyp',
                        'secret' => 'PQLjc67R23HENkuvNmgj2YY3QVn1OcBHXNtIJvpP',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..vpXoWKe5G89iZBXzCU-2Vg.TCxfAUtzwrwrc9oHqANNHBbV5GMtSOoJa7f6FtsYLB3M6O41GFV3WFLHwtYyQFONtDfL91mNmLGX07oOfj3y4A3YNUOyz3RrUU3Dgs3ph5HFAK4W28AfZNfzgzcxdLrIVm0aM9k_7AwVNMuVszbVcM7n4745_gSreqh-tRo8ebBv3fBgNkxVUhYblwDpuFw_IcGCpKogShOB4ryePJjH2G_9J0sBfppIcUO8_HCtST4SGs8gwIR_gJl3uAUjh0EKJZj-vmkB8yGm-q5J_R1X_AfiZIdwTmQoQmpgfet45MBbSVc7belMAlomO35srF2BiyOmGj2uVIuSbeHqeosMeQYWE2dPDS1KYgiBIhGl68NXRVNR8XdpPWX10gAEfHxpNf-zFnljV8Uu6Xqmvx9PG8VkmJDeMEVUHZPdZQODxqRutlLNldyvYt9JNhA4WyY9hQODmBMOjMLk9vX1jTL04SksfSvZ5qP93bjnjskqt9ycLaM-JC1-qcI0e3SEUwf2nrC-rRr48RTRhY-bpKRF3GAhicH5qtlVmyaoHHOpvTd1oItHRIkHjjcPuhPI-C2Ok8paJkDIfc_824bjgsXLiF2L9hcg1zQocMupxA_gsMw17Kjp5SqDS24CT_LB0LKnNzD9PKhW6NpiL_5KoNWkE5xqoG4bN6LDr0mvoo3GbUbBb_7PC6284UW1FhHvEhXxJIpWhSz_MgSKsBj-ALPZigpnppptQUXDDo9IYXm4QMQ.tfLGaJm9qtHYCXMPiypIRA',
                        'refresh_token' => 'AB11601186734nhbzLBjAS1qNW3EjDMWm81x5isrKY0c1VeCBY'
                    )
        ));
    }
}
