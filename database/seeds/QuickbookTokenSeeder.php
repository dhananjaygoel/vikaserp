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
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..u-z3bOmxrr8Hln0hujzZNw.UKp_uMKiJIMppy2EM9EuaM31udJEw6SWLs4hnePAX_-bZ3sbJFqXjgjcrQB9_Y5A6EkFeMJF_DV7M7jYqmaUu6ja95hrEIZUQxtP9709AzU-6m9pN3rKkHcvh6V9Xw6mLwa06Gr5mJikxfmVplYWTmXQO1H9GrrVW1dmje94w9h5aLzNWRaoqK_4mtVLW4R1Pq5xnYxsnxActIqoBcKGzmN-VRzcLPTBTSELcIgiE1VzTOilipM6UaV721PCq8dnz2a6WH-Dktr55oxI4SyXvqHdTisSizQMIloaN050O7zwOChAZS3nLmiUlQSNeXAkQX-icBgm9KnLP-p3GhUmtT8T35o1MNEjpQcUBWpTvN9qZ30yu3tHS82uY1aqvlwRxTIsuH9jrae5kAttzK31qGDnEjCDjY4Ti-E-BdhhxGFx4iLH88ATYRZfxGcZHHBWtTBYzkpuIVujKcqqN7HJvLq9zHyWXmW98XkZaxmE7dw-TUoBjqQQqGfN3w8fAb0pvgRgWB8COnTOqpBJdlAJ2ysvpDvP7_sndG1p2QwOcmQFLa34ZNBUrccTslnx3ZgJcuhSDsO0ogXn7KQdJchE5SK3Q9QapYjyb35QtIcacjk5c6qHrk9gFjnknCBT7dJsXeyOfTKHrZwtSZuQ0Eal-cIQ2pPTrVOUfYOxZyKpO3j-wM5yRnX4yFWJrpQrUZXGgivqDG7TWrEsxHuw_-kw4i5OhWL3xIQ5UJeAheD9f_4.I3cTou4vfDUAxTcf2BEv6A',
                        'refresh_token' => 'AB11601277621xsCRu0HuMt07LvdU8xQkxJSPK0zlfXwxEIF5t'
                    ),
                    //Plus GST account
                    array(
                        'id' => '2',
                        'client' => 'ABSSab3mSZlIGJLQ1tCZSKKpd7LN7OI3zacIfFW0hLsHLVMnyp',
                        'secret' => 'PQLjc67R23HENkuvNmgj2YY3QVn1OcBHXNtIJvpP',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..LvF9chYYml1MpqgDWYsujg.vf-yuOnlglCEyzqWTa_u0Xe3omA1RGSCuvpixgkNDnXeVADztmxwMB86Os1JlOGbFiT_hyHOwR93lSk2omO88Qs0KuCrWYjy0imjHKWu4uz2KVoSKxnW7fTlZzVSU71kuv3Z-2WQ_iuCMW_TNeQ8u85XH6ZWh9SH7gMM6kVrMHh87ugFfhu31gEJWNxNmQlkfV9-x6jGJ7VdHlFjksHYZS2jKKaayfJUO46HPN6DX9JFTRlsklCadRdccqdPwsUHB7TPVrvLvp1h8NrxbAxIoqLUByx-DRb4I8dmZFEYB6BoAfhQKyGskAJctV4Wq_lzIAvABzJ25NyPX15c6CnCneeFafXM8yuDeR_j0WLirREAZIgMIsdKuDnXH5DMaO1ToXW7JtIo6G3_VVaLB6xcsejm3UNqj3H6IY-z6PLecqXvyocVnDKpVn8_l0iRiuC5pNQixov1p-fqy5-storC3SYLYxDubDOzPBUs7Mw0DMagMwpJ5lWio_ARbp4L2dON7jCwR4U4Hu5P6JISselhsQ3BGqLscf_CsxvlqRU317OBVTgsR2YgZm0s7HXTG4T0Lin-zYSdHa4P-Nf_6yyrqMslLK3djCfMxzRi8AYU71b_0uW8vMQ3Apo7LHLIG8TQGQaVuSFEPEFtwM7QJrAg4I7DGaINsJH2Sm_R5nZgijF5UpixDKafJNrf-kLv60DaPW0AsjedUR6HAm1Y4eKN8UCt8GITFyIQXhgbrZDW1ac.2YcBLEmwt92HC1sFdFNSpQ',
                        'refresh_token' => 'AB11601277570V5T7r3loCI1p08aFK5DXZjPKALGrJlHPo0JQ3'
                    )
        ));
    }
}
