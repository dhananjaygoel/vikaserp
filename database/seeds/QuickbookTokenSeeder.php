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
                        'client' => 'ABOXTr7RAmUXVmeE82mITkaYze5K6dQmKSLKCZEoksLswpq6CF',
                        'secret' => 'uSaSnRgqEoZS5mv9U06V83iUKJhlSke8vAXPyi6i',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..XdZyn2nu0kQFDXQbRqJAcQ.rBPHk39qTyhDiwzmOLwm_2ueXtIE8wpykSUpiyNvLCbhVI75vz-3D37viYBlExcL8orH0OXSpJHrGxkckO0D8DqFEXvaApG1BXBo9Zxj5KDbtIXGJuWbDxz_tAReVUTLiHogbbshN2ieRJVXSqBCMJ8nWzintIaSCCF70-jYS7VvMeuI1hLiw_9SNIR8c8sUDnTLGFMYbznbBEI-BCP2PKZlx3k20nc5La9UUAwWwvc7jjIFLg324a6C9_ncIiOVjUJnBB18SCRHJZHMbGb5vSEdWROm3Kem4sJ6lpS-vVCHP7KW99NHX0UJLVwlmarpg7QdsFHq3lL7iRp54g6LtOa-yy3KTv2yi7lu2mkCgIjIsSW5-rEk6N0GN6fp-46S-DIfqhTjvJxRx-3VgI3pkwRXTu5SBgCLngPQOiluC4wbsWZAq_uAuGq-WCbDEoMSPJZqEgFd9nkCGPquzFOiuoG1P03K13CdY2HRohPwL3sXw6wOah_m8DwspGRU-DiUDbrLMN4an0LK44IT5lzuOAi7PtcXxK-ocwLEspGceuIDlQpWFTMYoA-17uHKPvtnF8hdfCGi-4V2KgJgxWCQbxr4GQOSBDg_YJsXwoVJ4N4BxBZdZuzSONfNLeFOhclNOEGCZiTv3W6TUrbduPkCSuN4oCMgty1AuXtQ8rccQxhIXpYuvKwCdzAmOlpNz4S29SG6kYlitjHF0BGva8LIN2ng2qfZzPL9ON8QBYhjCAY.WOYPYwEsTgHKQhNUw2_GVA',
                        'refresh_token' => 'AB11603118967nL22CQeWvUteTMdKeN3iT23kwlTODvl3I5dUE'
                    ),
                    //Plus GST account
                    array(
                        'id' => '2',
                        'client' => 'ABKxbBba5bH6RNqtT6d83ZW4wFtrtPVJq1XgB8T0qZ09RHdC1L',
                        'secret' => 'bygKYjkZhpGLm4JiStyNFKfC6ahlzBKKtvXDjSs8',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..1ZlkaECmf8dlNOWjikgOiA.ymrZxWKIZTMKLHSH3shKaWi590TjQzt4VTl0HjYZKvxv4C6umxwK0Mjz5XygPHMwyVvxyjzL8UV7sDdBDr3AjaRs0LFwPpce7VoXqo8-gAOrdSe2TqS6CXXEt8fQu9uCA38qB54wchWa_5Reotdzlhdgtqaxqz65UNmSNK5xtFdzMpQ9PVHaiEEVltnAogxKwXVTcBVMrPPUH1ocp9ArO6K1BDHe_TW6RoeV_uGI6xd6xYGNjOHQ09im5Q28wCZeAGKEFwnx_E7ZxLbgnwPS8mRyF_jI96wXCHB0ycg_3sxzB0RD5z2UVcdQutz62MNyBbIEP3vN-ECDtgJj1IRpv9rr9-t3_jSnLxRmiO317N5rff088olLrodqQdzBDX34hQ8I3WOeRCxUyLS4r3hz5ijSByS84SwBmC9PZAPdvl9N5ond9v_GxjxerPcXcvf8HkUhoqCTfLD5F45pWj8Rl38ndT6LWqua1M2J_AUix2JpxTzPF1vuxQ3P3OXyaBmCtL0fHIA-eYjDrikdNPCOq43tGHm6YL25Wfaga5TKQ-nUUQLW6Maj54jdoj0rb9DZfWxtrU2SqTLAUZn5N9NG1-cBTNe58akfyxdbjfJfAnZ6_Q5mPHwLCoak_rzphw4VEeWl_UB_uZz1XjqNFGLIbFnPGWuOmD0af7mg5QJ-vfrpaFLHwZ0Jpvw8ww7MPUTW21V9N5qqd3ngKMZwhulnN8Ldo_iuYXLob0aBB0YBgR8.QpxUPJCB3jWEW6SGXHoKqQ',
                        'refresh_token' => 'AB116031188783ZYZX41nCW02dTjf3f6gd4CxhYlZRDSjlve6s'
                    )
        ));
        echo 'success';
    }
}
