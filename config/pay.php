<?php

return [
    'alipay' => [
        'app_id' 		 => '2016092600603776',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA4q2d8axFtlT9dD4+R96za4oGAoymCxGcT2xwc3wihn+xDx3CnUKL8srYRctJjYaSZhFBM6/7CzfXOw0gpD8t2+KmHHA3cHLbNMAM+kmEPeD8BR6AFAG2HW1S/FXrWrf0cJ4ZrBWvEFkTCOkcTkEaJ9U/SIhiz8p/W/sR4swQucvy7qH/EYPesWCk0BPIhvN1VllscsQrNw0yU5khFNARL3vUt0gE6eZ1uXLK4DhyNIeFFIdEPtOF4QDaH+kKGJOhRhZJsFGvsKuTrfDVLr8jXIWq4yJ589UicRKTKmkdIC/KdPPpwJLXCRISrXk4Ib3KMTlf2/RgQeM1p7Jug2IWnwIDAQAB',
        'private_key'    => 'MIIEowIBAAKCAQEAzKMEYh9We4xwT6YBYE4R72byNPA9A8zqve4rXY7l7BtYJVJm1rylKdycAtGawEx8haExRbPowxiUjPEDiCxcAjp1ms9u5gT47HA7bebpCqG87trDREdDPlZDeJg0/wd05Cz6a2j0vjC3C/YNJtj+23kHXh9QlWxz4W5tJ12uMx8dukqlF9ZN1fv7C1tHQaGrlQeHJzhNIJk3dV3EmkcSycafbNGrC8Nw+qSm7ykjehIeyYFqE747+TwTRiMXu4OojbAxdLKy6xVS3zk7sp9uc+XPZchgLFEhGHQmJTCzxckMcduwdV6e2a6JYCi+ZbKff94b7Iw86V65vlt33/Vm8QIDAQABAoIBAQDKsQWcc1HkGlz8Z3+hdnLJYHyz9ulYpFY3PntxtiMDVKpgfVgjhsNJzZwyJo9Tve7xJZJ6ahM5e1VYrTlEXjvccS8f4isEZxRwjVIwL7HQuBc9ty4GqVdvnV5DSu/jU+1yPSLl1RXmdLY6gMLSIcl+dkjX78iP2NaHCivP/DiZpIZndylvLI+29DJmdpW1QYrn2Xoe9f53udm5SfuPlsqlPHAizTb/LGsCalTa1ydJVv5/yybHPaQ4xr75G+kotWtYSInD8TliY8Nt5YtMLlZ9AAcvnJMDkGA5Ifs/E1jVg9rUJ13/lI0VGAHV58rq4caRnkqaItjs9uJyOX2RZriBAoGBAP8MJEu0BHQRjND+D9KYgxeWjnHIh7Cs7Bq7gMMrykPV2OwjAtZiBMIxK7DGJo/YkDBZ8c+/EWXnVnm97nQgzemc6Q7PSalVZDv4wqGdeuQ0SPDzDt79daoR8od8sHiQKsJPeO7eN5MDKJZHz20OkHYFlz2Sqt//es3XEb3sI/75AoGBAM1mrR/z7f9EB0RCT0fIPO+XotbaWvbtyXw2uq8xGUzyBfztfSx2rzUAvehobKU3vq4yshF/00H1neufzxqjTgzwlsskUmdDsLQk2zlebvpw3gaSkyFRzPoJUDxKcRqLZAAqqKlJJvsEAV4IQplRV2VeZkZwWoQ3ktuIZz/kW425AoGAfXiKrpd8DWHI5dPnEtKmw0b1ArMFVfxsFuahfOKhGegtVFYRAkisUKCB/vAsbl266Z6GII69z2UUnMW8dnLg+gmQehGuClkQ+5PwpDNmDrhOHgNlEHyekzFLIC5OXrGF55vTQagerPtDz1K6j8s3dGhhtA/gclwsHPGuL8HpH3kCgYBxgJaUfJ/8miQF1TqoKP97Smjin0D7CMV5TWj13ITlnywt6zJrep4xBNrsbZ/z4I0PB9acku0zmr2Mcf/o8Wr0/ZHmaYjbpW0k7uTxm7xpyir2qNKC7Af/91uOJXjAuVwmN2yCN3lB5qG2y4u521gXHQYZUIbEX01Y4NRlWxQ4iQKBgG6+BTgbcibZxkLjyYA8fKfffOaExWmRLqt8YS63t70KxmfAFbPpVC2WXb323oC90RE8yHC+maCeF9V6cJ8JAfKuIDHjt2qpZ9E1rqoxuKPWF4kZMxWC2xOGxSCiXATAitku8o5TgF2+gYxBMLCPZB1vd9BnSO3V5fEuXToYclZv',
        'log'            => [
            'file' => storage_path('logs/alipay.log'),
        ],
    ],

    'wechat' => [
        'app_id'      => '',
        'mch_id'      => '',
        'key'         => '',
        'cert_client' => '',
        'cert_key'    => '',
        'log'         => [
            'file' => storage_path('logs/wechat_pay.log'),
        ],
    ],
];