import boto3

s3 = boto3.resource('s3')
data = open('/home/ubuntu/.ssh/id_rsa.pub', 'rb')
s3.Bucket('megabucketboy').put_object(Key='key.txt', Body=data)