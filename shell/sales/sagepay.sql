-- | sagepayreporting_fraud         |
-- | sagepaysuite_action            |
-- | sagepaysuite_debug             |
-- | sagepaysuite_fraud             |
-- | sagepaysuite_paypaltransaction |
-- | sagepaysuite_session           |
-- | sagepaysuite_tokencard         |
-- | sagepaysuite_transaction       |
-- | sagepaysuite_transaction_queue |

delete from sagepayreporting_fraud;
delete from sagepaysuite_action;
delete from sagepaysuite_debug;
delete from sagepaysuite_fraud;
delete from sagepaysuite_paypaltransaction;
delete from sagepaysuite_session;
delete from sagepaysuite_tokencard;
delete from sagepaysuite_transaction;
delete from sagepaysuite_transaction_queue;


insert into sagepayreporting_fraud select * from fashione_magento3.sagepayreporting_fraud;
insert into sagepaysuite_action select * from fashione_magento3.sagepaysuite_action;
insert into sagepaysuite_debug select * from fashione_magento3.sagepaysuite_debug;
insert into sagepaysuite_fraud select * from fashione_magento3.sagepaysuite_fraud;
insert into sagepaysuite_paypaltransaction select * from fashione_magento3.sagepaysuite_paypaltransaction;
insert into sagepaysuite_session select * from fashione_magento3.sagepaysuite_session;
insert into sagepaysuite_tokencard select * from fashione_magento3.sagepaysuite_tokencard;
insert into sagepaysuite_transaction select * from fashione_magento3.sagepaysuite_transaction;
insert into sagepaysuite_transaction_queue select * from fashione_magento3.sagepaysuite_transaction_queue;