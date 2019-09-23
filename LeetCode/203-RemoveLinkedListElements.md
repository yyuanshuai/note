删除链表中等于给定值 val 的所有节点。

示例:
```
    输入: 1->2->6->3->4->5->6, val = 6
    输出: 1->2->3->4->5
```

```
/**
 * Definition for singly-linked list.
 * public class ListNode {
 *     int val;
 *     ListNode next;
 *     ListNode(int x) { val = x; }
 * }
 */
 //普通链表解法-增加一个虚拟头结点
class Solution {
    public ListNode removeElements(ListNode head, int val) {
        ListNode dummyHead = new ListNode(-1);
        dummyHead.next = head;

        ListNode prev = dummyHead;
        while (prev.next != null){
            if(prev.next.val == val){
                prev.next = prev.next.next;
            }else {
                prev = prev.next;
            }
        }
        return dummyHead.next;
    }
}
```

```
//递归解法
class Solution {
    public ListNode removeElements(ListNode head, int val) {
        if(head == null){
            return null;
        }

        head.next = removeElements(head.next, val);
        return head.val == val ? head.next : head;
    }
}

```