-- RATING
    -- rating                        |
    -- rating_entity                 |
    -- rating_option                 |
    -- rating_option_vote            |
    -- rating_option_vote_aggregated |
    -- rating_store                  |
    -- rating_title                  |
 
delete from rating;  insert into rating select * from fashione_magento3.rating;
delete from rating_option;  insert into rating_option select * from fashione_magento3.rating_option;
delete from rating_entity;  insert into rating_entity select * from fashione_magento3.rating_entity;
delete from rating_option_vote;  insert into rating_option_vote select * from fashione_magento3.rating_option_vote;
delete from rating_option_vote_aggregated;  insert into rating_option_vote_aggregated select * from fashione_magento3.rating_option_vote_aggregated;
delete from rating_store;  insert into rating_store select * from fashione_magento3.rating_store;
delete from rating_title;  insert into rating_title select * from fashione_magento3.rating_title;


-- POLL 
    -- poll
    -- poll_answer
    -- poll_store
    -- poll_vote
delete from poll;
insert into poll select * from fashione_magento3.poll;
insert into poll_answer select * from fashione_magento3.poll_answer;
insert into poll_store select * from fashione_magento3.poll_store;
insert into poll_vote select * from fashione_magento3.poll_vote;