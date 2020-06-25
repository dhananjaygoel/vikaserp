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
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..uWyL2GOi9CFKKahfbAXgaQ.doRzv4G67E83IWmqapnN1IkKEWLO9e2r-egXgP3ik1QWSuqter_RNmdX7Auer0kajQ9NBAalZwrE7dPhXXIL3ObIEJ5_FTA6s170HWVs-ASu3Ck8O89PaTJri2Y6vMnM--1-4mWKpWcmBay4fSkNmfkqqqc6mxL31Rz3pw5Fk_pUZsU82bLxlYJjdfolNjsBqiSpYY-FhVH0XJD9rwPxTCmaDA3dDXdcjOYiUskqwo6CBGYe9u7aj09oocsET8RlEUhOEPkg3irdnSgYckBVixd0ymfdrW5oqoPmVc9WgxHBva-YnpVk453NlZXVElWkWNK3w95tfyWhEWGTK2QQbS3blwyL1U-uzwsORTX3Db7mX3_be4MUvXQog_9hxwtSA2-rlyjFXNQ2kLHaGs0q90AcddMJ2rc1W5f_NAiI0hO3krMGeTSPp8Bx4J7RKByuoNUXNCfzOW18SSN8bVYgzhmaHZV9RqJfaIDKghqKfcmnXFZjXkT1RRRe4iOjFcdiBNwhohbvB1GrTtLjCtQlanvZDPNcJmo8v2Udpq0Qku-DL0gc_-Up6Qg6hx6Hgra0lDEUU2CNhyQfRejSVqvDouSYJi7j8dqnYrzEXc4c4CL3hieNdt3vVMR8GE5wBPpmGkZBtvnw8fgRKW4XoWQZIdgds-vYofetujyG29zetF4XR53x_jKY3-tzR0SN7flvMhn9484niUqlXjNybZUwHfuC2X-FjQi5eskHQRVQssA.DZsoIYAV4FyQ7IZp1CKTeA',
                        'refresh_token' => 'AB116017994187QW0GFVZisv51LjIw1mEBJu8jl5lnbNpJCg0v'
                    ),
                    //Plus GST account
                    array(
                        'id' => '2',
                        'client' => 'ABKxbBba5bH6RNqtT6d83ZW4wFtrtPVJq1XgB8T0qZ09RHdC1L',
                        'secret' => 'bygKYjkZhpGLm4JiStyNFKfC6ahlzBKKtvXDjSs8',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..wOiw7m0R8Y3gUl6TjY0iwg.iHAh0cl3sds94L_DymEL38c7prI8cQv26IDGK3VpPUJuHLAheaoTwT8VGn7OWFF80QJDYvZfzVkS1hg2ev0i2EWmyC8t1cnGRDDT2R4FLP5xwb4co99rU7ZbIjiGGZ73b1tHgD7E6ylQXFn_qghm8kkguIpxZbpo-zMBjljLZERnkzcBtHF68dmtnXaT8Gtdg32NL8-z679uhX9Y4it6y6anY-zgY02X3XAgUR0ZYg-IBZhK2eObsc9SrU6RlpNK6MIsRavI9A3Pe-YniYd4huNSTaaHS78xHb0sIHjSe22plsCdkCYKqsjXJeJsGFPT3nPMtESREbWkGs8NrHarTEu_hzG_bjpNq3LEXbftMApjAhE9Wa8ZQNzetzoR8hGr_AAVPSuIiH800bVq7LZaEI1UAFCBS6k-T2KjckDsRIebpROFSg3OU0DDcv8vltKieuZqCHGQeIJtnUkp2uSQEVTmDcO8pybGOEG5W84O8SrBZ4-PUwC9arcJ72YCGftqTQ_rqGBA-HEQ_G4yQm8zRfIZD-peDGO6V8I1q-0tllnbXwCwB2sWnRF-vqt0i3gzBLRr_Hy2pjC-T96h47k2KvkxFBjoKPukwEResU0ZnZb_g6IsSRjVkPf2K_EKUYjisRd54Tq_WT52osfvEtxfDq9CfzoWDIID2E28CtI77V4lqU6b8etslrq4fWJDkF3F6ZK2lMNJ3FTJoXcK_nGtRSK1B8rt1xkd7fTkuStty-g.JWCcVMyZsTJFoNSTQVikEw',
                        'refresh_token' => 'AB11601799223RoeUD0h1F9UdnjiY3VDF3IRyXUyMCrwol7Vfv'
                    )
        ));
        echo 'success';
    }
}
