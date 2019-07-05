{
    "job": {
        "setting": {
            "speed": {
                 "channel": 5
            },
            "errorLimit": {
                "record": 0,
                "percentage": 0.02
            }
        },
        "content": [
            {
                "reader": {
                    "name": "mysqlreader",
                    "parameter": {
                        "username": "sync_user",
                        "password": "kingdee123456",
                        "splitPk": "",
                        "connection": [
                            {
"querySql":["select '6' as agentId,code,name,sales_organ,cmid,identity_code,phone,service_end_date,address,(case source when 'icrm' then 1 end ) as source,'09:00:00' as workStart,'18:00:00' as workEnd,now() as createTime from t_customer limit 10"],
                                "jdbcUrl": [
     "jdbc:mysql://172.18.152.46:3306/basicdata"
                                ]
                            }
                        ]
                    }
                },
               "writer": {
                    "name": "mysqlwriter",
                    "parameter": {
                        "username": "root",
                        "password": "3806c3d8a615529b",
                        "session": ["set session sql_mode='ANSI'","set names latin1"],
                        "column": [
                            "aId","cost","serveNote","service_account","companyNamewky","service_pwd","phone","ServiceEndTime","address","source","workStart","workEnd","createTime"
                        
                        ],
     
                        "connection": [
                            {
                                "table": [
                                    "user_wkytest"
                                ],
                                "jdbcUrl": 
     "jdbc:mysql://172.18.152.49:3306/bangwo8?characterEncoding=gbk&useUnicode=true"
                                
                            }
                        ]
                    }
                }
            }
        ]
    }
}
