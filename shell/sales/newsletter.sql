    -- | newsletter_problem                     |
    -- | newsletter_queue                       |
    -- | newsletter_queue_link                  |
    -- | newsletter_queue_store_link            |
    -- | newsletter_subscriber                  |
    -- | newsletter_template                    |

 -- select * from fashione_magento3.newsletter_problem;
 -- select * from fashione_magento3.newsletter_queue;
 -- select * from fashione_magento3.newsletter_template;
delete from newsletter_subscriber; insert into newsletter_subscriber select * from fashione_magento3.newsletter_subscriber;
