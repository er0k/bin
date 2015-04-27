#!/bin/bash
# mount an nfs share over ssh


# remote server. set it up in ~/.ssh/config
server=nfs-server
# remote directory to mount
rmount=/var/www
# local mount point
lmount=~/nfs-mount
# ssh socket control
socket=/tmp/nfs-sock

if [ "$1" == "mount" ]; then
    # find the nfs port. this changes every time nfs kernel is (re)started
    port=`ssh $server "rpcinfo -p localhost" | grep mountd | grep tcp | grep ' 1 ' | awk '{print $4}'`
    # start the ssh tunnels
    ssh -M -S $socket -fnNT -L 3049:localhost:$port -L 3050:localhost:2049 $server
    # mount the remote nfs
    sudo mount -t nfs -o port=3050,mountport=3049,tcp localhost:$rmount $lmount
elif [ "$1" == "umount" ]; then
    # unmount
    sudo umount -f $lmount
    # close tunnel
    ssh -S $socket -O exit $server
else
    echo "mount or umount?"
fi

