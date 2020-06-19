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
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..YNrmB4LXS-Qvk0rngNgFXw.BcaA1Hc3YGymoyOIr5NMeQRWNdxcaNxQUxplha9j-pNpvNLV8dCXrdTHnPBTPso1tGbOhfTp075gJk5gd15AXuwqm8-pAX4TAtqUDjvsYxgEd1j9AqLU-dgsUKiY_DdBFSFUO5sqdnohTewPqZEuD-7AVv9-WO5DN2LrbIbe1W1ihealnBFih2g-gS2R02C3tRJq8HXgMepkF3thihq4C0yNGYpQK9K5r6KccDEPQYRzv8Ptt84vK3sPDilWRohO1F50Afjop-_MBD3VacR0Mn0XhQ13p-7GsyrzXzxCi_N8RqRwfc63fXEaaXczrDglA7TJh7qCqXl5POqrpk2CGu4Jw5j7eB_DV66l-6POdsVCz4RNIdILZALNAi4HklJHXOhB0ayrp-AUESUsKlwEA6ECs9GqFaJ5keBJwpyg_z6b76eg9KKUFDWZLDoDjudzaj-QLVOGrWMsboYKWI5tO95dJ-ihVQGt_GLaEmeoIbWNeE4xhYKHYcReZ8g4HJdJPWK33m3H5-PiFKxscFc8O4VvRSNknmp_Aw30mSAXX728KV9cKUIPyP4eiLLZQtkH3cuzm60RpSOyONSlGId2iei97QKABm7kKQD1IL1jv0dTHyjJgoxj7rvSSQ6cjpd-JbBnZOMbH1BtLIdw5ai7UGx-GIjqJv1dYAq3gp_hs79HXQJlsxKLWaRzBtal5dtT0GDUlVCdChicYS9ajm6uSizFrJM_nJkB3-StrfHojOk.qC43BKbDaeYasS2EaEUl5w',
                        'refresh_token' => 'AB11601275667F298DLTisgrYJn6Ka5YxxvyNXJmBooSEE2PIQ'
                    ),
                    //Plus GST account
                    array(
                        'id' => '2',
                        'client' => 'ABSSab3mSZlIGJLQ1tCZSKKpd7LN7OI3zacIfFW0hLsHLVMnyp',
                        'secret' => 'PQLjc67R23HENkuvNmgj2YY3QVn1OcBHXNtIJvpP',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..Ey3EHhH9SDFUxzOlVJU8Hg.vezE0ITyY_u8_mRz_Ynl5e0ksjDUe6-nQfcQQUFX92CEw0Fq9WwbQqNl1LurwIw7Ce7vE7u53hAJn3TmtAkVgaZ7wGU0x6JPbS1bV65kEbCRX-XlfOpbuccyjFqlNL7TJXZImLPJnsdyQS4BMtkU2YFzAVJKI5G4osHQIu2bUh_KqhZqGiEQGghazJmYO9k9xtihH3Tp2NqSg9uFDkXFLSPplgh4RoaImMFXGb4NuZakbaKxsR-7tcEAsaivD0vyth8ZOmROeG1eP42I3ZO0jsTp9kH30gG9UlWYmQib0MEvoEsNp7rUo9sLbej5Uk41rjunNtlM933K8W6U-8wQ8QufHiQuHdTHViSO07FCBY1B-VuNtnCb7fU68mcK0KpxJKUtQTtcYENMXOA1kQNdxzmMha0oLrFYYglgIVnlWN7l37YDcZVCmjv7c6eCNqHdnNM0x28pwlJAA0uWoW5zvFMNju1SONPqQjzC4khxzq-ZpMJKQjCpsHYbvB12P7TobQfZxN-1KUum-qBEQ-MaCTboL3eKT2i2HZbJ2sDwvDpCJpKCrNRC1Ci68LQXfenwQ0qOODIklIQ_N5zHUIY6DyQfGUDrTqxl56_HXAJnNQ_xmNmQ50p7HIUF-PhDiQQpJXnRGsB5h_eVlVf5vUd7qO0KGVmkfN_QXRI78U3GP8NWltS8OEFPH5QoFIN6crGNUGVs6hENjGsZLnoTk13MeZAiTrx2ZhO_ntlyY8y7Jk4.0wLEM8LwVYBYGP9QOATwUA',
                        'refresh_token' => 'AB11601275579Su0edrLyHfSsAbSBBtAWjgBEPsnGsneku475U'
                    )
        ));
    }
}
